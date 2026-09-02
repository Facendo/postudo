<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <title>Registro</title>
</head>
<body>
    <div style="padding: 20px; display: flex; justify-content: flex-end; font-size: 25px; font-weight: bold;">
           <a href="{{ url('/') }}" class="button_barra"><i class="fa-solid fa-house"></i></a>
    </div>

    <div>
            <section class="flash-messages-container" aria-live="polite"> 
            @if (session('success'))
                <div class="my-alert-style" role="alert">
                    <strong class="font-bold">¡Éxito!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="my-red-alert-style" role="alert">
                    <strong class="font-bold">¡Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
        </section>    
    </div>

<div class="registration-page-container">

    <form method="POST" action="{{ route('register') }}" class="registration-form">
        @csrf

        <h2 class="registration-form-title">Crear Cuenta</h2>

        <div class="form-group">
            <label for="name" class="form-label">Nombre</label>
            <input id="name" class="form-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Tu nombre completo">
            @error('name')
                <span class="form-error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="cedula" class="form-label">Cédula</label>
            <input id="cedula" class="form-input" type="text" name="cedula" value="{{ old('cedula') }}" required autocomplete="cedula" placeholder="12345678">
            @error('cedula')
                <span class="form-error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="tu.correo@ejemplo.com">
            @error('email')
                <span class="form-error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Contraseña</label>
            <input id="password" class="form-input" type="password" name="password" required autocomplete="new-password" placeholder="Crea tu contraseña">
            @error('password')
                <span class="form-error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
            <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repite tu contraseña">
            @error('password_confirmation')
                <span class="form-error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="acciones-form">
            <a class="already-registered-link" href="{{ route('login') }}">¿Ya estás registrado?</a>
            <button type="submit" class="submit-button">
                Registrarse
            </button>
        </div>
    </form>
</div>

</body>
</html>