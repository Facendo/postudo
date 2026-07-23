<x-layout title='PostUDO || Registro de Sección'>
    <section class="login-page-container">
        <div class="registration-form">
            <h2 class="registration-form-title">Registro de Sección</h2>

            <form action="{{ route('administrador.gestionseccion.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="nro_seccion" class="form-label">Número de Sección</label>
                    <input type="number" id="nro_seccion" name="nro_seccion" class="form-input" placeholder="Ej: 1" min="1" required>
                </div>

                <input type="hidden" id="codigo_materia" name="codigo_materia" value="{{ $materia->codigo_materia }}">

                <div class="form-group">
                    <label for="hora_inicio" class="form-label">Hora de Inicio</label>
                    <input type="time" id="hora_inicio" name="hora_inicio" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="hora_fin" class="form-label">Hora de Fin</label>
                    <input type="time" id="hora_fin" name="hora_fin" class="form-input" required>
                </div>

                <div class="form-group">
                <label for="cedula_docente" class="form-label">Cédula del Profesor</label>
                <select id="cedula_docente" name="cedula_docente" class="form-select" required>
                    <option value="">Selecciona un profesor</option>
                    @foreach($profesores as $profesor)
                        <option value="{{ $profesor->cedula }}">
                            {{ $profesor->cedula }} - {{ $profesor->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('cedula_docente')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
                <div class="form-group">
                    <label for="aula" class="form-label">Aula</label>
                    <input type="text" id="aula" name="aula" class="form-input" placeholder="Ej: C-301" required>
                </div>

                <div class="form-group">
                    <label for="cupo_maximo" class="form-label">Cupo Máximo</label>
                    <input type="number" id="cupo_maximo" name="cupo_maximo" class="form-input" placeholder="Ej: 30" min="1" required>
                </div>
                
                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Registrar Sección</button>
                </div>
            </form>
        </div>
    </section>
</x-layout>