<x-layout title='PostUDO || Registro de Coordinador de Postgrado'>
    <section class="login-page-container">
        <div class="registration-form">
            <h2 class="registration-form-title">Registro de Coordinador de Postgrado</h2>

            <form action="{{ route('coordinador.coordinador_postgrado.store') }}" method="POST">
                @csrf
                {{-- Campo para Cédula --}}
                <div class="form-group">
                    <label for="cedula" class="form-label">Cédula</label>
                    <input type="text" id="cedula" name="cedula" class="form-input" placeholder="Ej: V-12345678"
                        value="{{ old('cedula') }}" required>
                    @error('cedula')
                        <span class="form-error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Campo para Nombre --}}
                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-input" placeholder="Nombre del coordinador"
                        value="{{ old('nombre') }}" required>
                    @error('nombre')
                        <span class="form-error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Campo para Apellido --}}
                <div class="form-group">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" id="apellido" name="apellido" class="form-input"
                        placeholder="Apellido del coordinador" value="{{ old('apellido') }}" required>
                    @error('apellido')
                        <span class="form-error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Campo para Correo Electrónico --}}
                <div class="form-group">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="email" id="correo" name="correo" class="form-input"
                        placeholder="ejemplo@coordinador.com" value="{{ old('correo') }}" required>
                    @error('correo')
                        <span class="form-error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Postgrado: relación 1:1 — exactamente UN postgrado por
                     coordinador (radio). Solo aparecen los que NO tienen
                     coordinador: los ocupados quedan fuera de la lista
                     (y store() los rechaza igual con notIn). --}}
                <div class="form-group">
                    <label class="form-label">Postgrado a asignar (exactamente 1)</label>

                    @forelse ($postgrados as $postgrado)
                        <label class="form-label"
                            style="display: block; margin-bottom: 8px; color: var(--texto); cursor: pointer;">
                            <input type="radio" name="postgrados[]" value="{{ $postgrado->id_postgrado }}"
                                style="margin-right: 8px;"
                                {{ in_array($postgrado->id_postgrado, old('postgrados') ?? []) ? 'checked' : '' }}>
                            {{ $postgrado->nombre }} ({{ $postgrado->id_postgrado }})
                        </label>
                    @empty
                        <p class="form-error-message">
                            No hay postgrados disponibles: todos ya tienen coordinador asignado
                            (o aún no se ha creado ninguno).
                        </p>
                    @endforelse

                    @error('postgrados')
                        <span class="form-error-message">{{ $message }}</span>
                    @enderror
                    @error('postgrados.*')
                        <span class="form-error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Botón de envío del formulario (inerte si no queda ningún
                     postgrado libre para asignar) --}}
                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button" {{ $postgrados->isEmpty() ? 'disabled' : '' }}>Registrar Coordinador</button>
                </div>

            </form>
        </div>
    </section>
</x-layout>
