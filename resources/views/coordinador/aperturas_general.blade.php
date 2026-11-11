<x-layout title='PostUDO || Apertura de Cohortes'>
    <div class="page-content" style="width: 90%; max-width: 1200px; margin: 40px auto; display: flex; flex-direction: column; align-items: center;">
        <div class="content_texto_bienvenida">
            <label>Apertura de Cohortes</label>
        </div>

        <p style="color: var(--texto); opacity: 0.8; text-align: center; margin-bottom: 18px; max-width: 760px;">
            Los coordinadores de postgrado solicitan cupos para crear cohortes. Al aprobar, esos cupos
            habilitan la creación de cohortes en su postgrado (se aprueba exactamente lo solicitado).
        </p>

        {{-- SOLICITUDES PENDIENTES --}}
        <h3 style="color: var(--texto); margin: 0 0 12px;">Pendientes de respuesta ({{ $pendientes->count() }})</h3>

        <table style="width: 100%;">
            <thead>
                <tr>
                    <th>Postgrado</th>
                    <th>Solicitante</th>
                    <th>Cupos</th>
                    <th>Justificación</th>
                    <th>Enviada</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pendientes as $solicitud)
                    <tr>
                        <td>{{ $solicitud->postgrado?->nombre ?? $solicitud->codigo_postgrado }}</td>
                        <td>
                            {{ $solicitud->solicitante?->name ?? '—' }}
                            @if ($solicitud->solicitante)
                                <br><small style="opacity: 0.7;">{{ $solicitud->solicitante->cedula }}</small>
                            @endif
                        </td>
                        <td><strong>{{ $solicitud->cupos }}</strong></td>
                        <td>{{ $solicitud->justificacion ?: '—' }}</td>
                        <td>{{ \Carbon\Carbon::parse($solicitud->created_at)->format('d/m/Y H:i') }}</td>
                        <td class="table-actions">
                            {{-- Aprobar: exactamente lo solicitado, sin ajustar cantidad --}}
                            <form action="{{ route('coordinador.apertura.aprobar', $solicitud) }}" method="POST" class="inline-form" style="display: inline-block; margin-right: 6px;">
                                @csrf
                                <button type="submit" class="btn-table-action" title="Aprobar solicitud"
                                    onclick="return confirm('¿Aprobar {{ $solicitud->cupos }} cupo(s) de cohorte para «{{ $solicitud->postgrado?->nombre ?? $solicitud->codigo_postgrado }}»?');">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>

                            {{-- Rechazar: pide el motivo con prompt() antes de enviar --}}
                            <form action="{{ route('coordinador.apertura.rechazar', $solicitud) }}" method="POST" class="inline-form" style="display: inline-block;" data-rechazar>
                                @csrf
                                <input type="hidden" name="motivo_rechazo" value="">
                                <button type="submit" class="btn-table-action" title="Rechazar solicitud" style="color: #dc3545;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px;">
                            No hay solicitudes pendientes. Aquí aparecerán las que envíen los coordinadores de postgrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- HISTORIAL DE SOLICITUDES RESPONDIDAS --}}
        <h3 style="color: var(--texto); margin: 32px 0 12px;">Historial de solicitudes</h3>

        <table style="width: 100%;">
            <thead>
                <tr>
                    <th>Postgrado</th>
                    <th>Solicitante</th>
                    <th>Cupos</th>
                    <th>Consumido</th>
                    <th>Restante</th>
                    <th>Estado</th>
                    <th>Respondida por</th>
                    <th>Respuesta</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($historial as $solicitud)
                    <tr>
                        <td>{{ $solicitud->postgrado?->nombre ?? $solicitud->codigo_postgrado }}</td>
                        <td>{{ $solicitud->solicitante?->name ?? '—' }}</td>
                        <td>{{ $solicitud->cupos }}</td>
                        <td>{{ $solicitud->consumido }}</td>
                        <td>{{ max($solicitud->cupos - $solicitud->consumido, 0) }}</td>
                        <td>
                            @if ($solicitud->estado === \App\Models\SolicitudAperturaCohorte::ESTADO_APROBADA)
                                <span style="padding: 3px 12px; border-radius: 999px; background: rgba(25, 135, 84, 0.15); color: #198754; font-weight: 600; font-size: 0.85rem;">Aprobada</span>
                            @else
                                <span style="padding: 3px 12px; border-radius: 999px; background: rgba(220, 53, 69, 0.15); color: #dc3545; font-weight: 600; font-size: 0.85rem;">Rechazada</span>
                            @endif
                        </td>
                        <td>{{ $solicitud->revisor?->name ?? '—' }}</td>
                        <td>
                            {{ $solicitud->respondido_en ? \Carbon\Carbon::parse($solicitud->respondido_en)->format('d/m/Y H:i') : '—' }}
                            @if ($solicitud->estado === \App\Models\SolicitudAperturaCohorte::ESTADO_RECHAZADA && $solicitud->motivo_rechazo)
                                <br><small style="opacity: 0.75;">Motivo: {{ $solicitud->motivo_rechazo }}</small>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 20px;">
                            Aún no se ha respondido ninguna solicitud.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        // Rechazo con motivo obligatorio: se pide con prompt() y se rellena
        // el input oculto antes de enviar (el form no sale sin motivo).
        document.querySelectorAll('form[data-rechazar]').forEach(form => {
            form.addEventListener('submit', evento => {
                if (form.dataset.confirmado === '1') return;

                evento.preventDefault();

                const motivo = prompt('Motivo del rechazo (obligatorio):');

                if (motivo === null) return; // cancelado

                if (!motivo.trim()) {
                    alert('Debes indicar un motivo de rechazo.');
                    return;
                }

                form.querySelector('input[name="motivo_rechazo"]').value = motivo.trim();
                form.dataset.confirmado = '1';
                form.submit();
            });
        });
    </script>
</x-layout>
