<x-layout title='PostUDO || Registro de Profesor'>
    <section class="login-page-container">
        <div class="registration-form">
            <h2 class="registration-form-title">Registro de Profesor</h2>

            <form action="{{route('administrador.gestion_profesor.store')}}" method="POST">
                @csrf
                {{-- Campo para Cédula --}}
                <div class="form-group">
                    <label for="cedula" class="form-label">Cédula</label>
                    <input type="text" id="cedula" name="cedula" class="form-input" placeholder="Ej: V-12345678" required>
                    @error('cedula')
                        <span class="form-error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Campo para Nombre --}}
                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-input" placeholder="Nombre del profesor" required>
                    @error('nombre')
                        <span class="form-error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Campo para Apellido --}}
                <div class="form-group">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" id="apellido" name="apellido" class="form-input" placeholder="Apellido del profesor" required>
                    @error('apellido')
                        <span class="form-error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Campo para Teléfono --}}
                <div class="form-group">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" class="form-input" placeholder="Ej: 0412-1234567" required>
                    @error('telefono')
                        <span class="form-error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Campo para Correo Electrónico --}}
                <div class="form-group">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="email" id="correo" name="correo" class="form-input" placeholder="ejemplo@profesor.com" required>
                    @error('correo')
                        <span class="form-error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Campo para Edad --}}
                <div class="form-group">
                    <label for="edad" class="form-label">Edad</label>
                    <input type="number" id="edad" name="edad" class="form-input" placeholder="Ej: 35" min="20" max="100" required>
                    @error('edad')
                        <span class="form-error-message">{{ $message }}</span>
                    @enderror
                </div>

                
                {{-- Botón de envío del formulario --}}
                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Registrar Profesor</button>
                </div>

            </form>
        </div>
    </section>
</x-layout>
