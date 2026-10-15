<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <link rel="shortcut icon" href="{{asset('iconopagina.png')}}" type="image/x-icon">
    <title>Login</title>

</head>

<body>
    <div
        style="padding: 20px; display: flex; justify-content: flex-end; font-size: 25px; font-weight: bold; border-radius: 10px;">
        <a href="{{ url('/') }}" class="button_barra"><i class="fa-solid fa-house"></i></a>
    </div>

    <div class="login-page-container">
        <form method="POST" action="{{ route('login') }}" class="login-form">
            @csrf

            <h2 class="login-form-title">Iniciar Sesión</h2>

            <div class="form-group">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required
                    autofocus autocomplete="username" placeholder="tu.correo@ejemplo.com">
                @error('email')
                    <span class="form-error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Contraseña</label>
                <input id="password" class="form-input" type="password" name="password" required
                    autocomplete="current-password" placeholder="Ingresa tu contraseña">
                @error('password')
                    <span class="form-error-message">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-acciones">
                <div class="links-izquierda">
                    <a href="{{ route('register') }}" class="register-link">¿No te has Registrado? Regístrate</a>

                    @if (Route::has('password.request'))
                        <a class="forgot-password-link" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                    @endif
                </div>

                <button type="submit" class="submit-button">
                    Iniciar Sesión
                </button>
            </div>
        </form>
    </div>
</body>

</html>