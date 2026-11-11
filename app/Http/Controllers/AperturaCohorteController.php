<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\FiltrablePorCoordinador;
use App\Models\Postgrado;
use App\Models\SolicitudAperturaCohorte;
use App\Services\NotificacionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * Apertura de cohortes por solicitud (2026-11-11).
 *
 * El coordinador de postgrado solicita cupos para SUS postgrados y el
 * coordinador general aprueba o rechaza (sin ajustar la cantidad: aprueba
 * exactamente lo pedido). No existe apertura directa: todo nace de una
 * solicitud. Las rutas parten en dos grupos por rol en routes/coordinador.php.
 */
class AperturaCohorteController extends Controller
{
    use FiltrablePorCoordinador;

    /**
     * Panel del coordinador de postgrado: formulario de nueva solicitud y
     * listado de SUS solicitudes (filtradas por sus postgrados, no por su
     * cuenta: así se ve el historial aunque se recree la cuenta).
     */
    public function index()
    {
        $permitidos = $this->idsPostgradosPermitidos(Auth::user()) ?? collect();

        $solicitudes = SolicitudAperturaCohorte::with(['postgrado', 'solicitante', 'revisor'])
            ->whereIn('codigo_postgrado', $permitidos->all())
            ->orderByDesc('created_at')
            ->get();

        $postgrados = Postgrado::whereIn('id_postgrado', $permitidos->all())
            ->orderBy('nombre')
            ->get();

        $cuposDisponibles = SolicitudAperturaCohorte::cuposDisponiblesEn($permitidos->all());

        return view('coordinador.aperturas_cohorte', compact('solicitudes', 'postgrados', 'cuposDisponibles'));
    }

    /**
     * Registra la solicitud (siempre pendiente) y avisa a los coordinadores
     * generales. El postgrado se valida contra SUS asignaciones: nunca se
     * confía en el que llega del formulario.
     */
    public function store(Request $request)
    {
        $permitidos = $this->idsPostgradosPermitidos(Auth::user()) ?? collect();

        if ($permitidos->isEmpty()) {
            return redirect()
                ->route('coordinador.apertura.index')
                ->with('error', 'No tienes postgrados asignados: no puedes solicitar la apertura de cohortes.');
        }

        $datos = $request->validate([
            'codigo_postgrado' => ['required', 'string', Rule::in($permitidos->all())],
            'cupos' => ['required', 'integer', 'min:1', 'max:50'],
            'justificacion' => ['nullable', 'string', 'max:1000'],
        ], [
            'codigo_postgrado.required' => 'Selecciona el postgrado para el que solicitas la apertura.',
            'codigo_postgrado.in' => 'Solo puedes solicitar apertura para tus postgrados asignados.',
            'cupos.required' => 'Indica cuántos cupos de cohorte necesitas.',
            'cupos.min' => 'Solicita al menos 1 cupo.',
        ]);

        $solicitud = new SolicitudAperturaCohorte;
        $solicitud->codigo_postgrado = $datos['codigo_postgrado'];
        $solicitud->cupos = $datos['cupos'];
        $solicitud->consumido = 0;
        $solicitud->estado = SolicitudAperturaCohorte::ESTADO_PENDIENTE;
        $solicitud->justificacion = $datos['justificacion'] ?? null;
        $solicitud->solicitado_por = Auth::id();
        $solicitud->save();

        app(NotificacionService::class)->solicitudAperturaCreada($solicitud);

        return redirect()
            ->route('coordinador.apertura.index')
            ->with('success', 'Solicitud enviada al coordinador general. Recibirás una notificación cuando la resuelva.');
    }

    /**
     * El coordinador de postgrado cancela su solicitud, pero solo mientras
     * esté pendiente (una vez respondida, el cupo ya es cosa del general).
     */
    public function destroy(SolicitudAperturaCohorte $solicitud)
    {
        $permitidos = $this->idsPostgradosPermitidos(Auth::user()) ?? collect();

        if (! $permitidos->contains($solicitud->codigo_postgrado)) {
            return redirect()
                ->route('coordinador.apertura.index')
                ->with('error', 'Esta solicitud no corresponde a tus postgrados.');
        }

        if ($solicitud->estado !== SolicitudAperturaCohorte::ESTADO_PENDIENTE) {
            return redirect()
                ->route('coordinador.apertura.index')
                ->with('error', 'Solo se pueden cancelar solicitudes pendientes.');
        }

        $solicitud->delete();

        return redirect()
            ->route('coordinador.apertura.index')
            ->with('success', 'Solicitud cancelada.');
    }

    /**
     * Bandeja del coordinador general: solicitudes pendientes de decidir y
     * historial de las ya resueltas (con lo que consumió cada una).
     */
    public function bandeja()
    {
        $pendientes = SolicitudAperturaCohorte::with(['postgrado', 'solicitante'])
            ->where('estado', SolicitudAperturaCohorte::ESTADO_PENDIENTE)
            ->orderBy('created_at')
            ->get();

        $historial = SolicitudAperturaCohorte::with(['postgrado', 'solicitante', 'revisor'])
            ->where('estado', '!=', SolicitudAperturaCohorte::ESTADO_PENDIENTE)
            ->orderByDesc('respondido_en')
            ->get();

        return view('coordinador.aperturas_general', compact('pendientes', 'historial'));
    }

    /**
     * Aprueba la solicitud tal cual (misma cantidad: el general no ajusta)
     * y avisa al solicitante. Solo de pendientes.
     */
    public function aprobar(SolicitudAperturaCohorte $solicitud)
    {
        if ($solicitud->estado !== SolicitudAperturaCohorte::ESTADO_PENDIENTE) {
            return redirect()
                ->route('coordinador.apertura.bandeja')
                ->with('error', 'Esta solicitud ya fue respondida.');
        }

        $solicitud->estado = SolicitudAperturaCohorte::ESTADO_APROBADA;
        $solicitud->revisado_por = Auth::id();
        $solicitud->respondido_en = now();
        $solicitud->save();

        app(NotificacionService::class)->solicitudAperturaRespondida($solicitud);

        return redirect()
            ->route('coordinador.apertura.bandeja')
            ->with('success', sprintf(
                'Apertura aprobada: %d cupo(s) de cohorte para «%s».',
                $solicitud->cupos,
                $solicitud->postgrado?->nombre ?? $solicitud->codigo_postgrado
            ));
    }

    /**
     * Rechaza la solicitud; el motivo es obligatorio (si no, el solicitante
     * no sabría qué corregir).
     */
    public function rechazar(Request $request, SolicitudAperturaCohorte $solicitud)
    {
        if ($solicitud->estado !== SolicitudAperturaCohorte::ESTADO_PENDIENTE) {
            return redirect()
                ->route('coordinador.apertura.bandeja')
                ->with('error', 'Esta solicitud ya fue respondida.');
        }

        $datos = $request->validate([
            'motivo_rechazo' => ['required', 'string', 'max:1000'],
        ], [
            'motivo_rechazo.required' => 'Indica el motivo del rechazo.',
        ]);

        $solicitud->estado = SolicitudAperturaCohorte::ESTADO_RECHAZADA;
        $solicitud->revisado_por = Auth::id();
        $solicitud->respondido_en = now();
        $solicitud->motivo_rechazo = $datos['motivo_rechazo'];
        $solicitud->save();

        app(NotificacionService::class)->solicitudAperturaRespondida($solicitud);

        return redirect()
            ->route('coordinador.apertura.bandeja')
            ->with('success', sprintf(
                'Solicitud de «%s» rechazada. El solicitante fue notificado.',
                $solicitud->postgrado?->nombre ?? $solicitud->codigo_postgrado
            ));
    }
}
