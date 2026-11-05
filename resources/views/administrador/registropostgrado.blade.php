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

                {{-- Sin «Descripción», «Duración» ni «Número de Cohortes»: la duración ya no
                     interesa y el total de cohortes se cuenta de las cohortes
                     reales creadas (no se declara al crear el postgrado). --}}
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