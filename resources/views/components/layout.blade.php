<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <title>{{$title}}</title>
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <div class="contenedor">
                    <img src="{{asset('Logo_UDO.png')}}" class="logo">
                </div>
                <div class="titulo_barra">
                    PostUDO
                </div>
                
                
                <a href="/" class="button_barra">Inicio</a>
                    @auth
                    <a href="{{route('logout')}}" class="button_barra">Cerrar sesion</a>
                    @endauth
                    @guest
                        <a href="{{route('login')}}" class="button_barra">Iniciar sesion</a>
                    @endguest
                
                
            </nav>
        </div>
    </header>

    <main>
        {{$slot}}
    </main>

    <footer id="pie">
            <div class="contenido_pie">
                <p>Pie de pagina</p>
            </div>
    </footer>
</body>
   
</html>