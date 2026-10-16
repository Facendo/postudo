<x-layout title='PostUDO || Registro Postgrado'>
    <section class="login-page-container">
        <div class="registration-form">
            <h2 class="registration-form-title">Registro de Postgrado</h2>

            @if ($errors->any())
                <div class="form-group" style="border: 1px solid #e05757; background: rgba(224, 87, 87, 0.15); border-radius: 8px; padding: 12px 16px;">
                    <strong>Revisa los siguientes campos:</strong>
                    <ul style="margin: 8px 0 0 18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

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

                <!-- Campo para el numero de cohortes -->
                <div class="form-group">
                    <label for="nro_de_cohortes" class="form-label">Número de Cohortes</label>
                    <input type="number" id="nro_de_cohortes" name="nro_de_cohortes" class="form-input" placeholder="Ej: 3" required>
                </div>
                @error('nro_de_cohortes')
                    <span class="error-message">{{ $message }}</span>
                @enderror
                <!-- Campo para el área al que pertenece el postgrado -->
                <div class="form-group">
                <label for="codigo_area" class="form-label">Área</label>
                <select id="codigo_area" name="codigo_area" class="form-select" required>
                    <option value="">Selecciona un área</option>
                    @foreach($areas as $area)
                        <option value="{{ $area->codigo }}" {{ old('codigo_area', $areaPreseleccionada ?? '') == $area->codigo ? 'selected' : '' }}>
                            {{ $area->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('codigo_area')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
                
                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Registrar Postgrado</button>
                </div>

            </form>
        </div>
        
    </section>
    <x-back_button />
</x-layout>