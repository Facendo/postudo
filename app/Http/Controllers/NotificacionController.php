<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Services\NotificacionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    public function __construct(private NotificacionService $notificaciones) {}

    /**
     * Bandeja de notificaciones del usuario de la sesión (todos los roles).
     * El alcance SIEMPRE es el usuario autenticado: jamás se confía en un
     * id de la URL para leer o marcar notificaciones ajenas.
     */
    public function index()
    {
        $notificaciones = Notificacion::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(20);

        $noLeidas = Notificacion::where('user_id', Auth::id())
            ->noLeidas()
            ->count();

        return view('notificaciones.index', compact('notificaciones', 'noLeidas'));
    }

    /**
     * JSON para el badge de la campanita: lo refresca el polling del panel
     * sin recargar la página (30 s).
     */
    public function contador()
    {
        return response()->json([
            'no_leidas' => Notificacion::where('user_id', Auth::id())
                ->noLeidas()
                ->count(),
        ]);
    }

    /**
     * Marca una notificación propia como leída.
     */
    public function leer(int $id)
    {
        Notificacion::where('user_id', Auth::id())
            ->findOrFail($id)
            ->marcarComoLeida();

        return back()->with('success', 'Notificación marcada como leída.');
    }

    /**
     * Marca todas las propias como leídas.
     */
    public function leerTodas()
    {
        Notificacion::where('user_id', Auth::id())
            ->noLeidas()
            ->update(['leida_en' => now()]);

        return back()->with('success', 'Todas las notificaciones se marcaron como leídas.');
    }

    /**
     * Formulario de comunicado + historial de los enviados por mí
     * (solo administrador y coordinador general: lo decide la ruta).
     */
    public function comunicar()
    {
        $conteo = $this->notificaciones->conteoComunicado();

        // Un comunicado genera N filas (una por destinatario): se agrupan
        // por contenido+fecha para mostrar «enviado a N personas». Dos
        // comunicados idénticos en el mismo segundo se agruparían juntos
        // (aceptado: es cosmético y muy improbable a mano).
        $enviados = Notificacion::where('emisor_id', Auth::id())
            ->where('tipo', 'comunicado')
            ->selectRaw('titulo, mensaje, created_at, COUNT(*) as destinatarios')
            ->groupBy('titulo', 'mensaje', 'created_at')
            ->orderByDesc('created_at')
            ->limit(30)
            ->get();

        return view('notificaciones.comunicar', compact('conteo', 'enviados'));
    }

    /**
     * Envía un comunicado general a estudiantes + coordinadores de postgrado.
     */
    public function enviarComunicado(Request $request)
    {
        $datos = $request->validate([
            'titulo' => ['required', 'string', 'max:150'],
            'mensaje' => ['required', 'string', 'max:2000'],
        ]);

        $total = $this->notificaciones->comunicadoGeneral(
            $datos['titulo'],
            $datos['mensaje'],
            Auth::user()
        );

        if ($total === 0) {
            return back()
                ->withInput()
                ->with('error', 'No hay destinatarios: aún no hay estudiantes ni coordinadores de postgrado con cuenta.');
        }

        return redirect()
            ->route('notificacion.index')
            ->with('success', "Comunicado enviado a {$total} destinatario(s).");
    }
}
