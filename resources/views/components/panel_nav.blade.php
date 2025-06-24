<div class="container">
            <nav>
                <div class="contenedor">
                <img src="{{ asset('../Logo_UDO.png') }}" alt="PostUDO Logo" class="logo">
                <h1 class="titulo_barra">PostUDO</h1>
                </div>
                    @auth
                        @if (Auth::user()->rol == 'estudiante')
                            <a href="{{ route('estudiante.index') }}" class="button_barra">Inicio</a>
                            <a href="{{ route('estudiante.perfil')}}" class="button_barra">Mi Perfil</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="cerrar_sesion">
                                Cerrar Sesión
                            </button>
                        </form>


                    @endauth
                    @guest
                        <a href="{{route('login')}}" class="button_barra">Iniciar sesion</a>
                    @endguest     
            </nav>
</div>