<x-layout title='PostUDO || Registro de Coordinador General'>
    <section class="login-page-container">
        <div class="registration-form">
            <h2 class="registration-form-title">Registro de Coordinador General</h2>

            <form action="{{route('administrador.coordinador.store')}}" method="POST">
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

                {{-- Botón de envío del formulario --}}
                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Registrar Coordinador</button>
                </div>

            </form>
        </div>
    </section>
</x-layout>