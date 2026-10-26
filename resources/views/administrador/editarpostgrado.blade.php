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

                {{-- Sin «Descripción» ni «Duración»: los campos desaparecieron del alta y del listado --}}
                <div class="form-group">
                    <label for="codigo_area" class="form-label">Área</label>
                    <select id="codigo_area" name="codigo_area" class="form-select" required>
                        @foreach($areas as $area)
                            <option value="{{ $area->codigo }}"
                                {{ old('codigo_area', $postgrado->codigo_area) == $area->codigo ? 'selected' : '' }}>
                                {{ $area->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('codigo_area')
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
