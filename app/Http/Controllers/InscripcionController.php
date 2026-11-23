<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\FiltrablePorCoordinador;
use App\Models\Cohorte;
use App\Models\Estudiante;
use App\Models\Inscripcion;
use App\Models\Preinscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Apartado «Inscripciones» del coordinador de postgrado (2026-11-23).
 *
 * Lista las preinscripciones APROBADAS de SUS postgrados y decide en dos
 * pasos:
 *   1) aprobar/rechazar al aspirante — aprobar crea la fila en
 *      'estudiantes' (espejos carrera/especialidad desde la mención);
 *   2) inscribirlo en una cohorte SOLO de la mención que eligió en la
 *      preinscripción (validado en el servidor, nunca por el cliente).
 *
 * Solo rol coordinador_postgrado (grupo role:coordinador_postgrado de
 * routes/coordinador.php); el filtrado por postgrados es el trait.
 */
class InscripcionController extends Controller
{
    use FiltrablePorCoordinador;

    /**
     * Listado: preinscripciones aprobadas de sus postgrados con su
     * estado de inscripción (sin fila = «pendiente»), más las cohortes
     * de las menciones en juego para los selects del paso 2.
     */
    public function index()
    {
        $permitidos = $this->idsPostgradosPermitidos(Auth::user());

        $preinscripciones = Preinscripcion::with('mencion.postgrado')
            ->where('estado', 'aprobada')
            ->when($permitidos, fn ($query) => $query->whereHas(
                'mencion',
                fn ($mencion) => $mencion->whereIn('id_postgrado', $permitidos)
            ))
            ->orderByDesc('created_at')
            ->get();

        // Emparejamiento por cédula NORMALIZADA: la preinscripción puede
        // llevar formato «V-30.123.456» mientras que inscripcion/estudiantes/
        // users guardan solo dígitos. Se resuelve en PHP (no hay forma
        // limpia de comparar así en SQL) y se adjunta como relación.
        $inscripciones = Inscripcion::whereIn(
            'cedula',
            $preinscripciones->map(fn ($p) => $this->cedulaNormalizada($p->cedula))->unique()
        )->get()->keyBy('cedula');

        $preinscripciones->each(
            fn ($p) => $p->setRelation(
                'inscripcion',
                $inscripciones->get($this->cedulaNormalizada($p->cedula))
            )
        );

        // Cohortes de las menciones listadas: cada fila filtra las suyas
        // en la vista (solo se puede inscribir en la mención del aspirante).
        $cohortes = Cohorte::with('mencion')
            ->whereIn('codigo_mencion', $preinscripciones->pluck('codigo_mencion')->filter()->unique())
            ->orderBy('codigo_cohorte')
            ->get();

        return view('coordinador.inscripciones', [
            'preinscripciones' => $preinscripciones,
            'cohortes' => $cohortes,
            'sinPostgradosAsignados' => $permitidos !== null && $permitidos->isEmpty(),
        ]);
    }

    /**
     * Paso 1a: aprueba al aspirante. Crea la fila en 'estudiantes'
     * (para que pueda registrarse) y sella el proceso en 'aprobada'.
     * Transacción + updateOrCreate/firstOrCreate: doble clic inofensivo.
     */
    public function aprobar(Preinscripcion $preinscripcion)
    {
        if (! $this->puedeGestionarPostgrado(Auth::user(), $preinscripcion->mencion?->id_postgrado)) {
            return redirect()
                ->route('coordinador.inscripciones.index')
                ->with('error', 'Esta preinscripción no pertenece a tus postgrados.');
        }

        // Sin mención no hay cohorte posible: no avanza (caso real solo
        // si se borró la mención después de la preinscripción).
        if ($preinscripcion->codigo_mencion === null) {
            return redirect()
                ->route('coordinador.inscripciones.index')
                ->with('error', 'El aspirante no tiene mención asociada: no puede aprobarse para inscripción.');
        }

        $cedula = $this->cedulaNormalizada($preinscripcion->cedula);
        $existente = Inscripcion::where('cedula', $cedula)->first();

        if ($existente?->estaInscrita()) {
            return redirect()
                ->route('coordinador.inscripciones.index')
                ->with('error', 'El aspirante ya está inscrito en una cohorte.');
        }

        DB::transaction(function () use ($preinscripcion, $cedula) {
            Inscripcion::updateOrCreate(
                ['cedula' => $cedula],
                ['estado' => 'aprobada', 'revisado_por' => Auth::id()]
            );

            // Alta del estudiante con los mismos espejos que el alta manual
            // (EstudianteController::store): carrera = postgrado,
            // especialidad = mención. Misma cédula normalizada que
            // 'estudiantes.cedula' y que el registro (max:8).
            Estudiante::firstOrCreate(
                ['cedula' => $cedula],
                [
                    'nombre' => $preinscripcion->nombre,
                    'apellido' => $preinscripcion->apellido,
                    'correo' => $preinscripcion->correo,
                    'edad' => $preinscripcion->edad,
                    'codigo_mencion' => $preinscripcion->codigo_mencion,
                    'carrera' => $preinscripcion->mencion?->postgrado?->nombre ?? 'N/A',
                    'especialidad' => $preinscripcion->mencion?->nombre ?? 'N/A',
                ]
            );
        });

        return redirect()
            ->route('coordinador.inscripciones.index')
            ->with('success', 'Aspirante aprobado. Ahora puedes inscribirlo en una cohorte de su mención.');
    }

    /**
     * Paso 1b: rechaza al aspirante. NO crea la fila en 'estudiantes'.
     */
    public function rechazar(Preinscripcion $preinscripcion)
    {
        if (! $this->puedeGestionarPostgrado(Auth::user(), $preinscripcion->mencion?->id_postgrado)) {
            return redirect()
                ->route('coordinador.inscripciones.index')
                ->with('error', 'Esta preinscripción no pertenece a tus postgrados.');
        }

        $existente = Inscripcion::where('cedula', $this->cedulaNormalizada($preinscripcion->cedula))->first();

        if ($existente?->estaInscrita()) {
            return redirect()
                ->route('coordinador.inscripciones.index')
                ->with('error', 'El aspirante ya está inscrito en una cohorte: no se puede rechazar.');
        }

        Inscripcion::updateOrCreate(
            ['cedula' => $this->cedulaNormalizada($preinscripcion->cedula)],
            ['estado' => 'rechazada', 'revisado_por' => Auth::id()]
        );

        return redirect()
            ->route('coordinador.inscripciones.index')
            ->with('success', 'Aspirante rechazado. Puedes reconsiderarlo más adelante.');
    }

    /**
     * Paso 2: inscribe al aspirante aprobado en una cohorte. La cohorte
     * debe pertenecer a la mención de SU preinscripción (se revalida aquí
     * aunque el select ya la filtraba en la vista).
     */
    public function inscribir(Request $request, Preinscripcion $preinscripcion)
    {
        if (! $this->puedeGestionarPostgrado(Auth::user(), $preinscripcion->mencion?->id_postgrado)) {
            return redirect()
                ->route('coordinador.inscripciones.index')
                ->with('error', 'Esta preinscripción no pertenece a tus postgrados.');
        }

        $datos = $request->validate([
            'codigo_cohorte' => ['required', 'string', 'exists:cohorte,codigo_cohorte'],
        ], [
            'codigo_cohorte.required' => 'Debes elegir una cohorte.',
            'codigo_cohorte.exists' => 'La cohorte elegida no existe.',
        ]);

        $inscripcion = Inscripcion::where('cedula', $this->cedulaNormalizada($preinscripcion->cedula))->first();

        if (! $inscripcion || $inscripcion->estado !== 'aprobada') {
            return redirect()
                ->route('coordinador.inscripciones.index')
                ->with('error', 'El aspirante debe estar aprobado antes de inscribirlo en una cohorte.');
        }

        $cohorte = Cohorte::find($datos['codigo_cohorte']);

        // Nunca confiar en el cliente: la cohorte debe ser de la mención
        // que el aspirante eligió en su preinscripción.
        if ((string) $cohorte->codigo_mencion !== (string) $preinscripcion->codigo_mencion) {
            return redirect()
                ->route('coordinador.inscripciones.index')
                ->with('error', 'La cohorte elegida no pertenece a la mención del aspirante.');
        }

        $inscripcion->update([
            'codigo_cohorte' => $cohorte->codigo_cohorte,
            'estado' => 'inscrita',
            'inscrito_por' => Auth::id(),
        ]);

        return redirect()
            ->route('coordinador.inscripciones.index')
            ->with('success', 'Estudiante inscrito en la cohorte '.$cohorte->codigo_cohorte.'.');
    }

    /**
     * Cédula a solo dígitos (quita el prefijo V/E/P/J/G y los separadores).
     *
     * Es la forma en que se guardan 'inscripcion.cedula',
     * 'estudiantes.cedula' y 'users.cedula', así el estudiante puede
     * consultar su estado con la cédula de su usuario (el registro admite
     * máximo 8 caracteres).
     */
    private function cedulaNormalizada(string $cedula): string
    {
        $sinPrefijo = (string) preg_replace('/^[VEPJG][\s.-]*/i', '', trim($cedula));
        $digitos = (string) preg_replace('/\D/', '', $sinPrefijo);

        return $digitos !== '' ? $digitos : trim($cedula);
    }
}
