<x-layout title='PostUDO || Comunicados'>

    <div class="page-content notificaciones-pagina"
        style="width: 90%; max-width: 900px; margin: 40px auto; display: flex; flex-direction: column; align-items: center;">

        {{-- Título de la sección --}}
        <div class="content_texto_bienvenida">
            <label>Comunicados Generales</label>
        </div>

        {{-- Formulario de envío --}}
        <div class="comunicado-form">
            <p class="comunicado-info">
                <i class="fa-solid fa-circle-info"></i>
                El comunicado se enviará a
                <strong>{{ $conteo['estudiantes'] }} estudiante(s)</strong> y a
                <strong>{{ $conteo['coordinadores'] }} coordinador(es) de postgrado</strong>
                <span class="comunicado-total">({{ $conteo['total'] }} destinatario(s) en total)</span>.
            </p>

            <form action="{{ route('notificacion.enviar') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" id="titulo" name="titulo" class="form-input" maxlength="150" required
                        placeholder="Ej.: Cambio de horario de atención" value="{{ old('titulo') }}">
                    @error('titulo')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="mensaje" class="form-label">Mensaje</label>
                    <textarea id="mensaje" name="mensaje" class="form-input" rows="5" maxlength="2000" required
                        placeholder="Escribe aquí el comunicado que verán los estudiantes y coordinadores…">{{ old('mensaje') }}</textarea>
                    @error('mensaje')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">
                        <i class="fa-solid fa-paper-plane"></i> Enviar comunicado
                    </button>
                </div>
            </form>
        </div>

        {{-- Historial de los comunicados enviados por mí --}}
        <div class="content_texto_bienvenida" style="margin-top: 40px;">
            <label>Comunicados enviados</label>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Mensaje</th>
                    <th style="text-align: center;">Destinatarios</th>
                    <th style="text-align: center;">Fecha</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($enviados as $enviado)
                    <tr>
                        <td>{{ $enviado->titulo }}</td>
                        <td class="notificacion-mensaje">{{ \Illuminate\Support\Str::limit($enviado->mensaje, 120) }}</td>
                        <td style="text-align: center;">
                            <span class="notificacion-tipo notificacion-tipo--comunicado">
                                <i class="fa-solid fa-users"></i> {{ $enviado->destinatarios }}
                            </span>
                        </td>
                        <td style="text-align: center; white-space: nowrap;">
                            {{ $enviado->created_at ? $enviado->created_at->format('d/m/Y H:i') : '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 24px;">
                            Aún no has enviado comunicados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-layout>
