<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{asset('iconopagina.png')}}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <title>PostUDO || Inicio</title>
    <style>
        :root {
            --bg-dark: #0a0303;
            --bg-grad-1: #1a0404;
            --bg-grad-2: #4a0808;
            --accent: #ff4d4d;
            --accent-hover: #ff1a1a;
            --text-main: #f0f0f0;
            --text-muted: #a0a0a0;
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.08);
            --glass-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            overflow-x: hidden;
            background: linear-gradient(135deg, var(--bg-dark) 0%, var(--bg-grad-1) 50%, var(--bg-grad-2) 100%);
            background-attachment: fixed;
            min-height: 100vh;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Outfit', sans-serif;
        }

        .ambient-light {
            position: fixed;
            border-radius: 50%;
            filter: blur(120px);
            z-index: -1;
            opacity: 0.5;
            animation: float 10s ease-in-out infinite;
        }

        .light-1 {
            top: -10%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: #ff0000;
        }

        .light-2 {
            bottom: -10%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: #7a0000;
            animation-delay: -5s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0);
            }

            50% {
                transform: translate(30px, 40px);
            }
        }

        /* Bottom Copyright */
        .bottom-copyright {
            position: absolute;
            bottom: 0px;
            width: 100%;
            text-align: center;
            padding: 20px;
            box-sizing: border-box;
        }

        .bottom-copyright p {
            color: rgba(255, 255, 255, 0.3);
            font-size: 0.95em;
            margin: 0;
            font-weight: 300;
            font-family: 'Outfit', sans-serif;
        }

        /* Top Floating Elements */
        .top-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 30px 60px;
            box-sizing: border-box;
            z-index: 10;
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            width: 45px;
            height: auto;
            filter: drop-shadow(0 0 10px rgba(255, 77, 77, 0.4));
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .titulo_barra {
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.5px;
            background: linear-gradient(to right, #fff, #ffb3b3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .nav-link {
            color: var(--text-main);
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            padding: 10px 18px;
            border-radius: 20px;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            background: transparent;
            display: inline-block;
        }

        .nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(5px);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #cc0000);
            color: #fff;
            padding: 12px 28px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(255, 77, 77, 0.3);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 77, 77, 0.5);
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-outline {
            background: transparent;
            color: #fff;
            padding: 11px 27px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            border: 2px solid var(--glass-border);
            transition: all 0.3s ease;
        }

        .btn-outline:hover {
            border-color: var(--accent);
            background: rgba(255, 77, 77, 0.1);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 120px 20px 40px;
            position: relative;
        }

        .hero-content {
            max-width: 900px;
            z-index: 10;
            animation: fadeUp 1s ease-out forwards;
        }

        .badge {
            display: inline-block;
            padding: 8px 16px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            color: #ffb3b3;
            margin-bottom: 25px;
            backdrop-filter: blur(10px);
        }

        .hero h1 {
            font-size: clamp(3rem, 6vw, 5rem);
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 25px;
            background: linear-gradient(to right, #fff, #ffb3b3, #ff4d4d);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .hero p {
            font-size: clamp(1.1rem, 2vw, 1.3rem);
            color: var(--text-muted);
            margin-bottom: 40px;
            max-width: 600px;
            margin-inline: auto;
            line-height: 1.6;
        }

        .hero-actions {
            display: flex;
            gap: 20px;
            justify-content: center;
        }



        /* Footer */
        footer {
            background: rgba(10, 3, 3, 0.9);
            border-top: 1px solid var(--glass-border);
            padding: 40px 20px;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.95rem;
            backdrop-filter: blur(10px);
        }

        /* Flash Messages */
        .flash-messages {
            position: fixed;
            top: 100px;
            right: 20px;
            z-index: 1100;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .alert {
            padding: 15px 25px;
            border-radius: 12px;
            backdrop-filter: blur(10px);
            color: #fff;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            animation: slideIn 0.5s cubic-bezier(0.25, 0.8, 0.25, 1) forwards;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.2);
            border: 1px solid rgba(40, 167, 69, 0.4);
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.4);
            border-left: 4px solid #dc3545;
        }

        @keyframes slideIn {
            from {
                transform: translateX(120%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeUp {
            from {
                transform: translateY(40px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .top-elements {
                padding: 20px;
            }

            .nav-links {
                display: none;
            }
        }




        /* Contenedor que cubre toda la pantalla sin generar scrollbars */
.glow-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    overflow: hidden;
    pointer-events: none; /* Permite hacer clic en los elementos de la interfaz a través de las luces */
    z-index: 0; /* Asegúrate de que esté por detrás de tu contenido */
}

/* Estilo base de los focos de luz */
.glow-light {
    position: absolute;
    width: 450px;
    height: 450px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(220, 38, 38, 0.6) 0%, rgba(153, 27, 27, 0.2) 50%, rgba(0, 0, 0, 0) 70%);
    filter: blur(80px); /* Crea el efecto difuminado suave */
    opacity: 0.8;
}

/* Luz 1: Esquina superior izquierda */
.glow-1 {
    top: -100px;
    left: -100px;
    animation: moverLuz1 5s infinite alternate ease-in-out;
}

/* Luz 2: Esquina inferior derecha */
.glow-2 {
    bottom: -100px;
    right: -100px;
    animation: moverLuz2 5s infinite alternate ease-in-out;
}

/* Keyframes para el movimiento de la primera luz */
@keyframes moverLuz1 {
    0% {
        transform: translate(0, 0) scale(1);
    }
    50% {
        transform: translate(150px, 100px) scale(1.2);
    }
    100% {
        transform: translate(50px, 250px) scale(0.9);
    }
}

/* Keyframes para el movimiento de la segunda luz */
@keyframes moverLuz2 {
    0% {
        transform: translate(0, 0) scale(1);
    }
    50% {
        transform: translate(-180px, -120px) scale(1.3);
    }
    100% {
        transform: translate(-80px, -220px) scale(1);
    }
}



    </style>
</head>

<body>

    <div class="ambient-light light-1"></div>
    <div class="ambient-light light-2"></div>

    <div class="glow-container">
        <div class="glow-light glow-1"></div>
        <div class="glow-light glow-2"></div>
    </div>

    <div class="top-elements">
        <div class="nav-left">
            <img src="{{ asset('../Logo_UDO.png') }}"
                onerror="this.src='https://via.placeholder.com/45x45/cc0000/ffffff?text=U'" alt="PostUDO Logo"
                class="logo">
            <div class="titulo_barra">PostUDO</div>
        </div>

        <div class="nav-links">
            @auth
                <a href="{{ url('/perfil') }}" class="nav-link">Mi Perfil</a>
                <a href="{{ url('/dashboard') }}" class="nav-link">Panel</a>
                <form action="{{ url('/logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-primary" style="padding: 10px 20px; font-size: 14px;">Cerrar Sesión <i
                            class="fa-solid fa-arrow-right-from-bracket ml-2"></i></button>
                </form>
            @endauth
            @guest
                <a href="{{ url('/login') }}" class="nav-link">Iniciar Sesión</a>
                <a href="{{ url('/register') }}" class="btn-primary">Registrarse</a>
            @endguest
        </div>
    </div>

    <main>
        <div class="flash-messages">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    <i class="fa-solid fa-circle-check"></i>
                    <div>
                        <strong>¡Éxito!</strong> {{ session('success') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error" role="alert">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <div>
                        <strong>¡Error!</strong> {{ session('error') }}
                    </div>
                </div>
            @endif
        </div>

        <section class="hero">
            <div class="hero-content">
                <h1>Coordinación de Estudios de Postgrado</h1>


            </div>
        </section>


    </main>

    <div class="bottom-copyright">
        <p>&copy; 2026 PostUDO - Todos los derechos reservados.</p>
    </div>


</body>

</html>