<div class="container">
            <nav>
                <div class="contenedor">
                    <img src="{{asset('Logo_UDO.png')}}" class="logo">
                </div>
                <div >
                    <img src="{{asset('tituloLogo.png')}}" class="logo_barra">
                </div>
                    @auth
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