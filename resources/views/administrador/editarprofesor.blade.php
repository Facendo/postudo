<x-layout title='PostUDO || Editar Profesor'>
    <section class="login-page-container">
        <div class="registration-form">
            <h2 class="registration-form-title">Editar Profesor</h2>

            <form action="{{ route('administrador.gestion_profesor.update', $profesor->cedula) }}" method="POST">
                @csrf
                @method('PUT') 
                {{-- Campo para Cédula (generalmente no editable si es la clave primaria) --}}
                <div class="form-group">
                    <label for="cedula" class="form-label">Cédula</label>
                    <input type="text" id="cedula" name="cedula" class="form-input" 
                           placeholder="Ej: V-12345678" value="{{ old('cedula', $profesor->cedula) }}" required>
                    {{-- 'readonly' para evitar que se cambie la cédula si es la clave primaria --}}
                    @error('cedula')
                        <span class="form-error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Campo para Nombre --}}
                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-input" 
                           placeholder="Nombre del profesor" value="{{ old('nombre', $profesor->nombre) }}" required>
                    @error('nombre')
                        <span class="form-error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Campo para Apellido --}}
                <div class="form-group">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" id="apellido" name="apellido" class="form-input" 
                           placeholder="Apellido del profesor" value="{{ old('apellido', $profesor->apellido) }}" required>
                    @error('apellido')
                        <span class="form-error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Campo para Teléfono --}}
                <div class="form-group">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" class="form-input" 
                           placeholder="Ej: 0412-1234567" value="{{ old('telefono', $profesor->telefono) }}" required>
                    @error('telefono')
                        <span class="form-error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Campo para Correo Electrónico --}}
                <div class="form-group">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="email" id="correo" name="correo" class="form-input" 
                           placeholder="ejemplo@profesor.com" value="{{ old('correo', $profesor->correo) }}" required>
                    @error('correo')
                        <span class="form-error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Campo para Edad --}}
                <div class="form-group">
                    <label for="edad" class="form-label">Edad</label>
                    <input type="number" id="edad" name="edad" class="form-input" 
                           placeholder="Ej: 35" min="20" max="100" value="{{ old('edad', $profesor->edad) }}" required>
                    @error('edad')
                        <span class="form-error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Campo para Número de Sección --}}
                
                {{-- Botón de envío del formulario --}}
                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Actualizar Profesor</button>
                </div>

            </form>
        </div>
    </section>
</x-layout>
