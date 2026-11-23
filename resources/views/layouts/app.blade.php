<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Entrada fluida al cargar (2026-11-21): misma coreografía que el
             resto de la app — barra desde arriba, contenido desde abajo.
             Va en línea para NO tocar el bundle de Vite (exigiría
             npm run build). `both` + keyframes que terminan en `transform:
             none` → al terminar no queda transform retenido que pueda
             romper a un hijo position: fixed (el modal de borrar cuenta). --}}
        <style>
            @keyframes fadeDown {
                from {
                    transform: translateY(-20px);
                    opacity: 0;
                }

                to {
                    transform: none;
                    opacity: 1;
                }
            }

            @keyframes fadeUp {
                from {
                    transform: translateY(24px);
                    opacity: 0;
                }

                to {
                    transform: none;
                    opacity: 1;
                }
            }

            .min-h-screen>nav {
                animation: fadeDown 0.6s ease-out 0s both;
            }

            .min-h-screen>header,
            .min-h-screen>main {
                animation: fadeUp 0.6s ease-out 0.1s both;
            }

            @media (prefers-reduced-motion: reduce) {

                .min-h-screen>nav,
                .min-h-screen>header,
                .min-h-screen>main {
                    animation: none;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-brand-soft dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
