<x-layout title='PostUDO || Editar Postgrado'>
    <section class="login-page-container">
        <div class="registration-form">
            <h2 class="registration-form-title">Editar Postgrado</h2>

            {{-- El action del formulario apunta a la ruta 'update' y utiliza el método PUT/PATCH --}}
            <form action="{{ route('administrador.gestion_postgrado.update', $postgrado->id_postgrado) }}" method="POST">
                @csrf
                @method('PUT') {{-- O @method('PATCH') --}}

                <!-- Campo para Id_postgrado (generalmente no se edita, pero se muestra) -->
                <div class="form-group">
                    <label for="Id_postgrado" class="form-label">ID del Postgrado</label>
                    <input type="text" id="Id_postgrado" name="Id_postgrado" class="form-input" value="{{ old('id_postgrado', $postgrado->id_postgrado) }}" readonly>
                    {{-- 'readonly' para evitar que se edite el ID principal --}}
                </div>

                <!-- Campo para nombre del postgrado -->
                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre del Postgrado</label>
                    <input type="text" id="nombre" name="nombre" class="form-input" placeholder="Ej: Maestría en Ciencias de la Computación" value="{{ old('nombre', $postgrado->nombre) }}" required>
                    @error('nombre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campo para descripcion -->
                <div class="form-group">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea id="descripcion" name="descripcion" class="form-input" placeholder="Breve descripción del postgrado..." rows="4" required>{{ old('descripcion', $postgrado->descripcion) }}</textarea>
                    @error('descripcion')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campo para duracion -->
                <div class="form-group">
                    <label for="duracion" class="form-label">Duración</label>
                    <input type="text" id="duracion" name="duracion" class="form-input" placeholder="Ej: 2 años / 4 semestres" value="{{ old('duracion', $postgrado->duracion) }}" required>
                    @error('duracion')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campo para codigo_especialidad -->
                <div class="form-group">
                    <label for="codigo_especialidad" class="form-label">Especialidad</label>
                    <select id="codigo_especialidad" name="codigo_especialidad" class="form-select" required>
                        @foreach($especialidades as $especialidad)
                            <option value="{{ $especialidad->codigo_especialidad }}" 
                                {{ old('codigo_especialidad', $postgrado->codigo_especialidad) == $especialidad->codigo_especialidad ? 'selected' : '' }}>
                                {{ $especialidad->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('codigo_especialidad')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Actualizar Postgrado</button>
                    {{-- Botón para cancelar y volver al listado --}}
                    <a href="{{ route('administrador.gestion_postgrado') }}" class="button_body secondary-button">Cancelar</a>
                </div>

            </form>
        </div>
    </section>
</x-layout>
