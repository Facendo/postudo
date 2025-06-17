<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="shortcut icon" href="{{asset('iconopagina.png')}}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <title>{{$title}}</title>
</head>
<body>
    <header>
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
                    
                
                
            </nav>
        </div>
    </header>

    <main>
        <div >
         {{-- ***** AQUÍ AGREGAS LA PARTE DE LOS MENSAJES FLASH ***** --}}
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
        </div>
        {{$slot}}
        
    </main>

    <footer id="pie">
            <div class="contenido_pie">
                <p>&copy; PostUDO</p>
            </div>
    </footer>
</body>
   
</html>