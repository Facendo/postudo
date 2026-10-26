<?php

namespace App\Services;

use App\Models\CoordinadorPostgrado;
use App\Models\Estudiante;
use App\Models\Mencion;
use App\Models\Notificacion;
use App\Models\Pagos;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Servicio central de notificaciones in-app.
 *
 * Toda la resolución de destinatarios vive aquí (nunca en los controllers):
 *
 *  - Solicitudes del estudiante (pago registrado) -> coordinador de SU
 *    postgrado + todos los administradores.
 *  - Estado (pago verificado) -> de vuelta al estudiante que lo registró.
 *  - Comunicados (administrador / coordinador general) -> todos los
 *    estudiantes + todos los coordinadores de postgrado.
 *
 * El fan-out es de una fila por destinatario en la tabla `notificacion`.
 * Casos tolerados: estudiante sin mención, postgrado sin coordinador,
 * coordinador que aún no tiene cuenta `users` o estudiante sin cuenta:
 * simplemente no reciben (no se lanza error).
 */
class NotificacionService
{
    /**
     * El estudiante registró un pago (queda «Pendiente» de verificación).
     * Destinos: coordinador de su postgrado + administradores.
     */
    public function pagoRegistrado(Pagos $pago, ?User $emisor = null): int
    {
        $destinatarios = $this->coordinadoresDelEstudiante($pago->cedula)
            ->merge($this->administradores())
            ->unique('id');

        // La página de pagos es solo de administradores: al coordinador de
        // postgrado se le notifica SIN enlace para que no caiga en «no
        // tienes permisos» (decisión D6 del plan).
        return $this->emitir(
            $destinatarios,
            tipo: 'solicitud',
            titulo: 'Nuevo pago registrado',
            mensaje: sprintf(
                '%s registró el pago «%s» por Bs. %s (referencia %s). Pendiente de verificación.',
                $pago->nombre,
                $pago->asunto,
                number_format((float) $pago->monto, 2, ',', '.'),
                $pago->referencia
            ),
            emisor: $emisor,
            enlace: fn (User $destinatario) => $destinatario->rol === 'administrador'
                ? route('pago.controlpagos')
                : null
        );
    }

    /**
     * El administrador verificó/actualizó el pago: aviso de vuelta al
     * estudiante que lo registró.
     */
    public function pagoVerificado(Pagos $pago, ?User $emisor = null): int
    {
        $estudiante = User::where('cedula', $pago->cedula)->first();

        if (! $estudiante) {
            return 0; // no tiene cuenta: no hay a quién notificarle
        }

        return $this->emitir(
            collect([$estudiante]),
            tipo: 'estado',
            titulo: 'Pago verificado',
            mensaje: sprintf(
                'Tu pago «%s» por Bs. %s fue verificado y ya está registrado.',
                $pago->asunto,
                number_format((float) $pago->monto, 2, ',', '.')
            ),
            emisor: $emisor,
            // El histórico de pagos es del propio estudiante: enlace
            // válido solo si su rol es realmente estudiante.
            enlace: fn (User $destinatario) => $destinatario->rol === 'estudiante'
                ? route('pago.index')
                : null
        );
    }

    /**
     * Comunicado general del administrador o del coordinador general:
     * a todos los estudiantes y a todos los coordinadores de postgrado.
     * Devuelve el nº de destinatarios a los que se le notificó.
     */
    public function comunicadoGeneral(string $titulo, string $mensaje, User $emisor): int
    {
        $destinatarios = User::whereIn('rol', ['estudiante', 'coordinador_postgrado'])->get();

        return $this->emitir(
            $destinatarios,
            tipo: 'comunicado',
            titulo: $titulo,
            mensaje: $mensaje,
            emisor: $emisor,
            enlace: fn () => null // lo lee desde la campanita/bandeja
        );
    }

    /**
     * Conteos para el preview del formulario de comunicados
     * («Se enviará a N estudiantes y M coordinadores»).
     *
     * @return array{estudiantes: int, coordinadores: int, total: int}
     */
    public function conteoComunicado(): array
    {
        $estudiantes = User::where('rol', 'estudiante')->count();
        $coordinadores = User::where('rol', 'coordinador_postgrado')->count();

        return [
            'estudiantes' => $estudiantes,
            'coordinadores' => $coordinadores,
            'total' => $estudiantes + $coordinadores,
        ];
    }

    /**
     * Coordinadores de postgrado del estudiante, como filas `users`.
     * Cadena: estudiante → mención → postgrado → pivote de asignación →
     * coordinador (por cédula) → su cuenta. Si en algún eslabón falta,
     * devuelve una colección vacía.
     *
     * @return Collection<int, User>
     */
    private function coordinadoresDelEstudiante(string $cedula): Collection
    {
        $estudiante = Estudiante::find($cedula);

        if (! $estudiante || ! $estudiante->codigo_mencion) {
            return collect(); // sin mención/postgrado: no hay coordinador
        }

        $mencion = Mencion::find($estudiante->codigo_mencion);

        if (! $mencion) {
            return collect();
        }

        $cedulasCoordinadores = CoordinadorPostgrado::query()
            ->whereHas('postgrados', function ($query) use ($mencion) {
                // Columna calificada: la pivote usa codigo_postgrado, pero
                // se califica para que el SQL no dependa de adivinanzas.
                $query->where('postgrado.id_postgrado', $mencion->id_postgrado);
            })
            ->pluck('cedula');

        if ($cedulasCoordinadores->isEmpty()) {
            return collect(); // postgrado sin coordinador asignado
        }

        // Solo los que ya se registraron (tienen fila en `users`).
        return User::whereIn('cedula', $cedulasCoordinadores)->get();
    }

    /**
     * @return Collection<int, User>
     */
    private function administradores(): Collection
    {
        return User::where('rol', 'administrador')->get();
    }

    /**
     * Inserta una fila por destinatario (en lote) y devuelve cuántas.
     *
     * @param  Collection<int, User>  $destinatarios
     * @param  callable(User): ?string  $enlace  enlace por destinatario
     *                                           (null = sin enlace: puede que el destino no le abra).
     */
    private function emitir(Collection $destinatarios, string $tipo, string $titulo, string $mensaje, ?User $emisor, callable $enlace): int
    {
        if ($destinatarios->isEmpty()) {
            return 0;
        }

        $ahora = now();

        $filas = $destinatarios
            ->filter(fn (User $destinatario) => $destinatario->id !== $emisor?->id) // nadie se notifica a sí mismo
            ->map(fn (User $destinatario) => [
                'user_id' => $destinatario->id,
                'emisor_id' => $emisor?->id,
                'tipo' => $tipo,
                'titulo' => $titulo,
                'mensaje' => $mensaje,
                'enlace' => $enlace($destinatario),
                'leida_en' => null,
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ])
            ->values();

        if ($filas->isEmpty()) {
            return 0;
        }

        Notificacion::insert($filas->all());

        return $filas->count();
    }
}
