<x-layout title='PostUDO || Apertura de Cohortes'>
    <div class="page-content" style="width: 90%; max-width: 1200px; margin: 40px auto; display: flex; flex-direction: column; align-items: center;">
        <div class="content_texto_bienvenida">
            <label>Apertura de Cohortes</label>
        </div>

        {{-- Resumen de cupos: solo se crean cohortes dentro de este número --}}
        <p style="margin: 0 0 6px; padding: 8px 18px; border: 1px solid var(--borde); background: var(--superficie); border-radius: 999px; color: var(--texto);">
            Cupos de apertura disponibles: <strong>{{ $cuposDisponibles }}</strong>
        </p>

        @if ($cuposDisponibles < 1 && $postgrados->isNotEmpty())
            <p style="margin: 0 0 18px; padding: 12px 18px; border: 1px solid rgba(255, 193, 7, 0.55); background: rgba(255, 193, 7, 0.12); border-radius: 8px; color: #b45309; text-align: center;">
                Todavía no tienes cupos. Solicita la apertura al coordinador general con el formulario de abajo:
                él decidirá si aprueba los cupos que pidas.
            </p>
        @endif

        {{-- Formulario de nueva solicitud (solo si tiene postgrados asignados) --}}
        @if ($postgrados->isEmpty())
            <p style="margin: 0 0 22px; padding: 12px 18px; border: 1px solid rgba(255, 193, 7, 0.55); background: rgba(255, 193, 7, 0.12); border-radius: 8px; color: #b45309;">
                No tienes postgrados asignados: no puedes solicitar la apertura de cohortes.
                Contacta con el coordinador general.
            </p>
        @else
            <form action="{{ route('coordinador.apertura.store') }}" method="POST" class="registration-form" style="width: 100%; max-width: 720px; margin-bottom: 25px; display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
                @csrf
                <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 220px;">
                    <label for="codigo_postgrado" class="form-label">Postgrado</label>
                    <select id="codigo_postgrado" name="codigo_postgrado" class="form-input" required>
                        @foreach ($postgrados as $postgrado)
                            <option value="{{ $postgrado->id_postgrado }}" @selected(old('codigo_postgrado') == $postgrado->id_postgrado)>
                                {{ $postgrado->nombre }} ({{ $postgrado->id_postgrado }})
                            </option>
                        @endforeach
                    </select>
                    @error('codigo_postgrado')
                        <p class="error-message" style="margin-top: 6px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 0; width: 160px;">
                    <label for="cupos" class="form-label">Cupos (cohortes)</label>
                    <input type="number" id="cupos" name="cupos" class="form-input" min="1" max="50" value="{{ old('cupos', 1) }}" required>
                    @error('cupos')
                        <p class="error-message" style="margin-top: 6px;">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="submit-button" style="min-width: 170px;">Enviar Solicitud</button>

                <div class="form-group" style="margin-bottom: 0; flex: 1 1 100%;">
                    <label for="justificacion" class="form-label">Justificación (opcional)</label>
                    <textarea id="justificacion" name="justificacion" class="form-input" rows="2" maxlength="1000" placeholder="Ej: 3 cohortes para el inicio de marzo 2027">{{ old('justificacion') }}</textarea>
                    @error('justificacion')
                        <p class="error-message" style="margin-top: 6px;">{{ $message }}</p>
                    @enderror
                </div>
            </form>
        @endif

        {{-- Solicitudes enviadas --}}
        <h3 style="color: var(--texto); margin: 10px 0 12px;">Mis solicitudes</h3>

        <table>
            <thead>
                <tr>
                    <th>Postgrado</th>
                    <th>Cupos</th>
                    <th>Estado</th>
                    <th>Enviada</th>
                    <th>Respuesta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($solicitudes as $solicitud)
                    <tr>
                        <td>{{ $solicitud->postgrado?->nombre ?? $solicitud->codigo_postgrado }}</td>
                        <td>{{ $solicitud->cupos }}</td>
                        <td>
                            @if ($solicitud->estado === \App\Models\SolicitudAperturaCohorte::ESTADO_APROBADA)
                                <span style="padding: 3px 12px; border-radius: 999px; background: rgba(25, 135, 84, 0.15); color: #198754; font-weight: 600; font-size: 0.85rem;">Aprobada</span>
                            @elseif ($solicitud->estado === \App\Models\SolicitudAperturaCohorte::ESTADO_RECHAZADA)
                                <span style="padding: 3px 12px; border-radius: 999px; background: rgba(220, 53, 69, 0.15); color: #dc3545; font-weight: 600; font-size: 0.85rem;">Rechazada</span>
                            @else
                                <span style="padding: 3px 12px; border-radius: 999px; background: rgba(255, 193, 7, 0.18); color: #b45309; font-weight: 600; font-size: 0.85rem;">Pendiente</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($solicitud->created_at)->format('d/m/Y H:i') }}</td>
                        <td>
                            @if ($solicitud->estado === \App\Models\SolicitudAperturaCohorte::ESTADO_PENDIENTE)
                                Esperando respuesta del coordinador general
                            @elseif ($solicitud->estado === \App\Models\SolicitudAperturaCohorte::ESTADO_APROBADA)
                                Aprobada por {{ $solicitud->revisor?->name ?? 'coordinador general' }}
                                el {{ $solicitud->respondido_en ? \Carbon\Carbon::parse($solicitud->respondido_en)->format('d/m/Y') : '—' }}
                                <br><small style="opacity: 0.75;">Consumidos: {{ $solicitud->consumido }} de {{ $solicitud->cupos }}</small>
                            @else
                                Rechazada por {{ $solicitud->revisor?->name ?? 'coordinador general' }}
                                el {{ $solicitud->respondido_en ? \Carbon\Carbon::parse($solicitud->respondido_en)->format('d/m/Y') : '—' }}
                                <br><small style="opacity: 0.75;">Motivo: {{ $solicitud->motivo_rechazo }}</small>
                            @endif
                        </td>
                        <td class="table-actions">
                            @if ($solicitud->estado === \App\Models\SolicitudAperturaCohorte::ESTADO_PENDIENTE)
                                <form action="{{ route('coordinador.apertura.destroy', $solicitud) }}" method="POST" class="inline-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-table-action" title="Cancelar solicitud" onclick="return confirm('¿Cancelar esta solicitud de apertura?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px;">
                            Aún no has solicitado aperturas de cohortes.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layout>
