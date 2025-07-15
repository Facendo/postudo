<x-layout title='PostUDO || Registro Postgrado'>
    <section class="login-page-container">
        <div class="registration-form">
            <h2 class="registration-form-title">Registro de Postgrado</h2>

            <form action="{{ route('administrador.gestion_postgrado.store') }}" method="POST">
                @csrf
                <!-- Campo para Id_postgrado -->
                <div class="form-group">
                    <label for="Id_postgrado" class="form-label">ID del Postgrado</label>
                    <input type="text" id="Id_postgrado" name="Id_postgrado" class="form-input" placeholder="Ej: PG-INF-2025" required>
                </div>

                <!-- Campo para nombre del postgrado -->
                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre del Postgrado</label>
                    <input type="text" id="nombre" name="nombre" class="form-input" placeholder="Ej: Maestría en Ciencias de la Computación" required>
                </div>

                <!-- Campo para descripcion -->
                <div class="form-group">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea id="descripcion" name="descripcion" class="form-input" placeholder="Breve descripción del postgrado..." rows="4" required></textarea>
                </div>

                <!-- Campo para duracion -->
                <div class="form-group">
                    <label for="duracion" class="form-label">Duración</label>
                    <input type="text" id="duracion" name="duracion" class="form-input" placeholder="Ej: 2 años / 4 semestres" required>
                </div>

                <!-- Campo para codigo_especialidad (similar al de estudiante pero para postgrados) -->
                <div class="form-group">
                <label for="codigo_especialidad" class="form-label">Especialidad</label>
                <select id="codigo_especialidad" name="codigo_especialidad" class="form-select" required>
                    <option value="">Selecciona una especialidad</option>
                    @foreach(App\Models\Especialidades::with('carrera')->get() as $especialidad)
                        <option value="{{ $especialidad->codigo_especialidad }}">
                            {{ $especialidad->nombre }} - {{ $especialidad->carrera->nombre ?? 'Sin carrera' }}
                        </option>
                    @endforeach
                </select>
                @error('codigo_especialidad')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
                
                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Registrar Postgrado</button>
                </div>

            </form>
        </div>
    </section>
</x-layout>