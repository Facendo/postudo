<x-layout>
    <div class="registration-form">
        <h2 class="registration-form-title">Editar Perfil de Estudiante</h2>
    <form method="POST" action="{{ route('estudiante.perfil_update') }}" class="edit-profile-form" enctype="multipart/form-data">
        @csrf
        @method('PUT') {{-- Use PUT method for updates --}}

        <h2 class="titulo sub-titulo">Editar Perfil</h2>

        <div class="form-group">
            <label for="name" class="form-label">Nombre</label>
            <input id="name" class="form-input" type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" placeholder="Tu nombre completo">
            @error('name')
                <span class="form-error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="cedula" class="form-label">Cédula</label>
            <input id="cedula" class="form-input" type="text" name="cedula" value="{{ old('cedula', $user->cedula) }}" required autocomplete="cedula" placeholder="12345678">
            @error('cedula')
                <span class="form-error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input id="email" class="form-input" type="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username" placeholder="tu.correo@ejemplo.com">
            @error('email')
                <span class="form-error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Nueva Contraseña (dejar en blanco para no cambiar)</label>
            <input id="password" class="form-input" type="password" name="password" autocomplete="new-password" placeholder="Deja en blanco para no cambiar">
            @error('password')
                <span class="form-error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
            <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" autocomplete="new-password" placeholder="Repite la nueva contraseña">
            @error('password_confirmation')
                <span class="form-error-message">{{ $message }}</span>
            @enderror
        
        <div class="form-actions">
            <button type="submit" class="submit-button">
                Actualizar Perfil
            </button>
        </div>
    </form>
</div>
</x-layout>