<div class="top-elements">
    <div class="contenedor">
        <img src="{{ asset('../Logo_UDO.png') }}" alt="PostUDO Logo" class="logo">
        <h1 class="titulo_barra">PostUDO</h1>
    </div>

    <div class="menu_barra_nueva">
        @auth
                {{-- El botón «Inicio» no aparece en el índice propio del panel
                     (ya estás en él); vuelve en cualquier otra página interna
                     del perfil. --}}
                @php
                    $esIndiceDelPanel = request()->routeIs(
                        'estudiante.index',
                        'administrador.index',
                        'coordinador.index',
                        'coordinador_postgrado.index'
                    );
                @endphp

                @if (! $esIndiceDelPanel)
                    @if (Auth::user()->rol == 'estudiante')
                        <a href="{{ route('estudiante.index') }}" class="button_barra">Inicio</a>
                    @endif

                    @if (Auth::user()->rol == 'administrador')
                        <a href="{{ route('administrador.index') }}" class="button_barra">Inicio</a>
                    @endif

                    @if (Auth::user()->rol == 'coordinador_general')
                        <a href="{{ route('coordinador.index') }}" class="button_barra">Inicio</a>
                    @endif

                    @if (Auth::user()->rol == 'coordinador_postgrado')
                        <a href="{{ route('coordinador_postgrado.index') }}" class="button_barra">Inicio</a>
                    @endif
                @endif

                {{-- Campanita de notificaciones: visible en los 4 roles.
                     El badge lo refresca el polling de abajo cada 30 s. --}}
                @php
                    $notificacionesSinLeer = Auth::user()->notificaciones()->noLeidas()->count();
                @endphp
                <a href="{{ route('notificacion.index') }}" class="campanita-notificaciones" id="campanitaNotificaciones"
                    title="Notificaciones">
                    <i class="fa-solid fa-bell"></i>
                    <span class="campanita-badge {{ $notificacionesSinLeer > 0 ? '' : 'campanita-badge--vacio' }}"
                        id="campanitaBadge">
                        {{ $notificacionesSinLeer }}
                    </span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="cerrar_sesion">
                        Cerrar Sesión
                    </button>
                </form>
            </div>

        @endauth
    @guest
        <a href="{{ url('/') }}" class="button_barra">Inicio</a>
        {{-- En la planilla de preinscripción y en el catálogo público de
             postgrados no se ofrece iniciar sesión; el resto público sí. --}}
        @if (! request()->routeIs('preinscripcion.*', 'postgrado.index'))
            <a href="{{route('login')}}" class="button_barra">Iniciar sesion</a>
        @endif
    @endguest



</div>

@auth
    {{-- Polling ligero: refresca el contador del badge cada 30 s sin
         recargar la página (no hay websockets en este entorno). Solo
         actualiza el número y la clase de «vacío»; la bandeja se abre
         con el propio enlace de la campanita. --}}
    <script>
        (function () {
            const badge = document.getElementById('campanitaBadge');
            if (!badge || !window.fetch) return;

            const url = @json(route('notificacion.contador'));

            setInterval(async () => {
                try {
                    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) return; // sesión caída u otro error: se ignora

                    const datos = await res.json();
                    badge.textContent = datos.no_leidas;
                    badge.classList.toggle('campanita-badge--vacio', datos.no_leidas === 0);
                } catch (e) {
                    // Sin conexión: se reintenta en el siguiente ciclo.
                }
            }, 30000);
        })();
    </script>
@endauth