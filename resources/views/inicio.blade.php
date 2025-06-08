<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inicio</title>
</head>
<body>
    @auth
        <div>
            <a href="{{route('login')}}">Inicio de sesion</a>
        </div>
        @endauth
    <h1>Inicio</h1>
</body>
</html>