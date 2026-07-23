<x-layout title='PostUDO || Editar Sección'>
    <section class="login-page-container">
        <div class="registration-form">
            <h2 class="registration-form-title">Editar Sección</h2>

            <form action="{{ route('administrador.gestionseccion.update', $seccion->nro_seccion) }}" method="POST">
                @csrf
                @method('PUT') {{-- Use PUT method for updates --}}

                <div class="form-group">
                    <label for="nro_seccion" class="form-label">Número de Sección</label>
                    <input type="number" id="nro_seccion" name="nro_seccion" class="form-input" placeholder="Ej: 1" value="{{ old('nro_seccion', $seccion->nro_seccion) }}" required>
                    {{-- Note: If nro_seccion is your primary key and should not be changed, you might want to make this input readonly or a hidden field if it's strictly an identifier. --}}
                </div>

                <div class="form-group">
                    <label for="codigo_materia" class="form-label">Código de Materia</label>
                    <input type="number" id="codigo_materia" name="codigo_materia" class="form-input" placeholder="Ej: 12345" value="{{ old('codigo_materia', $seccion->codigo_materia) }}" required>
                </div>

                <div class="form-group">
                    <label for="hora_inicio" class="form-label">Hora de Inicio</label>
                    <input type="time" id="hora_inicio" name="hora_inicio" class="form-input" value="{{ old('hora_inicio', \Carbon\Carbon::parse($seccion->hora_inicio)->format('H:i')) }}" required>
                </div>

                <div class="form-group">
                    <label for="hora_fin" class="form-label">Hora de Fin</label>
                    <input type="time" id="hora_fin" name="hora_fin" class="form-input" value="{{ old('hora_fin', \Carbon\Carbon::parse($seccion->hora_fin)->format('H:i')) }}" required>
                </div>

                <div class="form-group">
                    <label for="cedula_docente" class="form-label">Cédula del Docente</label>
                    <input type="text" id="cedula_docente" name="cedula_docente" class="form-input" placeholder="Ej: V-12345678" value="{{ old('cedula_docente', $seccion->cedula_docente) }}" required>
                </div>

                <div class="form-group">
                    <label for="aula" class="form-label">Aula</label>
                    <input type="text" id="aula" name="aula" class="form-input" placeholder="Ej: C-301" value="{{ old('aula', $seccion->aula) }}" required>
                </div>

                <div class="form-group">
                    <label for="cupo_maximo" class="form-label">Cupo Máximo</label>
                    <input type="number" id="cupo_maximo" name="cupo_maximo" class="form-input" placeholder="Ej: 30" min="1" value="{{ old('cupo_maximo', $seccion->cupo_maximo) }}" required>
                </div>
                
                <div class="form-group">
                    <label for="cupo_actual" class="form-label">Cupo Actual</label>
                    <input type="number" id="cupo_actual" name="cupo_actual" class="form-input" placeholder="Ej: 5" min="0" value="{{ old('cupo_actual', $seccion->cupo_actual) }}" required>
                    {{-- Consider if cupo_actual should be directly editable or updated through other actions --}}
                </div>

                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Actualizar Sección</button>
                </div>
            </form>
        </div>
    </section>
</x-layout>