<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('iconopagina.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <title>{{ $title ?? 'PostUDO' }}</title> {{-- Added a default title if $title is not set --}}
     <script src="https://kit.fontawesome.com/your-font-awesome-kit-code.js" crossorigin="anonymous"></script> {{-- Reemplaza con tu propio código de kit --}}
    {{-- Incluye tu archivo JavaScript externo --}}
    <script src="{{ asset('js/admin_options.js') }}"></script>
</head>
<body>
    <header class="main-header"> {{-- Add a class for specific header styling if needed --}}
        <x-panel_nav></x-panel_nav>
    </header>

    <main class="main-content"> {{-- Add a class for main content area styling --}}
        {{-- Flash Messages Section --}}
        <section class="flash-messages-container" aria-live="polite"> {{-- Use a section and aria-live for accessibility --}}
            @if (session('success'))
                <div class="my-alert-style" role="alert"> {{-- role="alert" for accessibility --}}
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

        {{ $slot }} {{-- This is where the specific page content will be injected --}}

    </main>

    <footer id="pie" class="main-footer"> {{-- Add a class for consistency --}}
        <div class="footer-content"> {{-- More descriptive class name --}}
            <p>&copy; {{ date('Y') }} PostUDO. Todos los derechos reservados.</p> {{-- Dynamic year for copyright --}}
        </div>
    </footer>

    {{-- Optional: Add any global JavaScript files here, before the closing </body> tag --}}
    <script src="{{ asset('js/app.js') }}"></script>

</body>
</html>