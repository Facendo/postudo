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
            /* --- Paleta de prueba UDO (2026-10-20): blanco + naranja + azul ---
               Solo afecta a esta página de inicio; el resto de la app usa public/css/style.css */
            --udo-blanco: #ffffff;
            --udo-azul: #003da5;
            --udo-azul-marino: #001233;
            --udo-naranja: #f7941d;
            --udo-naranja-suave: #ffd9b8;

            --bg-dark: #001233;
            --bg-grad-1: #001a4d;
            --bg-grad-2: #003da5;
            --accent: #f7941d;
            --accent-hover: #ff9f2e;
            --accent-soft: #ffd9b8;
            --text-main: #f2f5f9;
            --text-muted: #9fb3d1;
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.08);
            --glass-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);

            /* Espacio reservado al menú lateral fijo (ancho aprox. del menú + margen).
               El hero usa este valor por IZQUIERDA y DERECHA → el título queda
               centrado al 100% en el viewport. Si el menú crece (texto más largo),
               sube este valor para que el título no lo pise. */
            --lateral-ancho: 480px;
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
            background: #2563eb;
        }

        .light-2 {
            bottom: -10%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: #0a3d91;
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
            filter: drop-shadow(0 0 10px rgba(247, 148, 29, 0.45));
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .titulo_barra {
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.5px;
            background: linear-gradient(to right, #fff, var(--accent-soft));
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
            background: linear-gradient(135deg, var(--accent), #d97306);
            color: #fff;
            padding: 12px 28px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(247, 148, 29, 0.35);
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
            box-shadow: 0 6px 20px rgba(247, 148, 29, 0.5);
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
            background: rgba(247, 148, 29, 0.12);
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
            color: var(--accent-soft);
            margin-bottom: 25px;
            backdrop-filter: blur(10px);
        }

        .hero h1 {
            font-size: clamp(3rem, 5vw, 5rem);
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 25px;
            background: linear-gradient(to right, #fff, var(--accent-soft), var(--accent));
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
            background: rgba(0, 10, 32, 0.92);
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

        /* Menú lateral de enlaces de la portada (preinscripción, postgrados...) */
        /* Sidebar fijo al borde izquierdo de la ventana; en pantallas <= 1439px vuelve al flujo */
        .menu-lateral {
            position: fixed;
            left: 30px;
            top: 50%;
            transform: translateY(-50%);
            width: max-content;
            max-width: calc(100vw - 60px);
            display: flex;
            flex-direction: column;
            gap: 14px;
            text-align: left;
            z-index: 100;
            animation: fadeUpLateral 1s ease-out forwards;
        }

        /* Misma entrada que fadeUp pero conservando el centrado vertical (translateY(-50%)) */
        @keyframes fadeUpLateral {
            from {
                transform: translateY(calc(-50% + 40px));
                opacity: 0;
            }

            to {
                transform: translateY(-50%);
                opacity: 1;
            }
        }

        @media (max-width: 1439px) {
            /* Sin sidebar: el menú queda debajo del título, como al inicio */
            .hero {
                flex-direction: column;
            }

            .menu-lateral {
                position: static;
                width: 100%;
                max-width: 460px;
                margin: 40px auto 0;
                transform: none;
                animation: fadeUp 1s ease-out forwards;
            }
        }

        @media (min-width: 1440px) {
            /* Centrado real del título: mismo espacio a izquierda y derecha,
               reservando el del menú fijo → el h1 cae en el centro del viewport */
            .hero {
                padding-left: var(--lateral-ancho);
                padding-right: var(--lateral-ancho);
            }
        }

        .menu-lateral-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 22px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 14px;
            backdrop-filter: blur(10px);
            color: var(--text-main);
            text-decoration: none;
            font-weight: 500;
            font-size: 18px;
            overflow-wrap: anywhere;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        a.menu-lateral-item:hover,
        button.menu-lateral-item:hover {
            border-color: var(--accent);
            background: rgba(247, 148, 29, 0.1);
            transform: translateY(-2px);
        }

        .menu-lateral-item i {
            color: var(--accent);
            width: 22px;
            text-align: center;
        }

        .menu-lateral-item.menu-texto {
            cursor: default;
            color: var(--text-muted);
        }

        /* Modal «Aranceles» de la portada (contenido fijo: vigente desde el 11 de abril de 2025) */
        .modal-info-fondo {
            position: fixed;
            inset: 0;
            background: rgba(0, 6, 20, 0.75);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            padding: 20px;
        }

        .modal-info-caja {
            background: var(--bg-grad-1);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            max-width: 640px;
            width: 100%;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
            padding: 0;
            box-shadow: var(--glass-shadow);
            text-align: left;
        }

        .modal-info-cabecera {
            padding: 25px 30px 18px;
            border-bottom: 1px solid var(--glass-border);
            text-align: center;
            flex-shrink: 0;
        }

        .modal-info-caja h3 {
            color: #fff;
            margin-bottom: 0;
        }

        /* Cuerpo con scroll propio: el modal nunca se sale de la pantalla */
        .modal-info-cuerpo {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            padding: 22px 30px;
            text-align: left;
        }

        .modal-info-caja p {
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .modal-info-intro {
            margin-bottom: 18px;
        }

        .modal-info-intro strong {
            color: var(--text-main);
        }

        /* Aviso destacado: se paga a la tasa BCV del día */
        .modal-info-aviso {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background: rgba(247, 148, 29, 0.1);
            border: 1px solid rgba(247, 148, 29, 0.35);
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 22px;
            color: var(--text-main);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .modal-info-aviso i {
            color: var(--accent);
            margin-top: 3px;
        }

        .modal-info-grupo {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 20px 0 8px;
        }

        .modal-info-grupo i {
            color: var(--accent);
        }

        .modal-info-lista {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        .modal-info-fila {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            gap: 15px;
            padding: 8px 0;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.08);
            color: var(--text-main);
            font-size: 0.95rem;
        }

        .modal-info-fila:last-child {
            border-bottom: none;
        }

        .modal-info-precio {
            color: var(--accent);
            font-weight: 700;
            white-space: nowrap;
        }

        /* Párrafo suelto dentro de un grupo (texto del comunicado) */
        .modal-info-nota {
            margin: 6px 0 10px;
            color: var(--text-muted);
            font-size: 0.92rem;
            line-height: 1.6;
        }

        .modal-info-pie {
            padding: 15px 30px 22px;
            border-top: 1px solid var(--glass-border);
            text-align: center;
            flex-shrink: 0;
        }

        @media (max-width: 600px) {
            .modal-info-cabecera,
            .modal-info-cuerpo,
            .modal-info-pie {
                padding-left: 18px;
                padding-right: 18px;
            }

            /* En móvil el precio baja debajo del concepto */
            .modal-info-fila {
                flex-direction: column;
                align-items: flex-start;
                gap: 2px;
            }
        }

        .modal-info-cerrar {
            background: transparent;
            border: 1px solid var(--glass-border);
            color: #fff;
            font-family: 'Outfit', sans-serif;
            padding: 10px 30px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .modal-info-cerrar:hover {
            border-color: var(--accent);
            background: rgba(247, 148, 29, 0.12);
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
    background: radial-gradient(circle, rgba(37, 99, 235, 0.6) 0%, rgba(10, 47, 120, 0.25) 50%, rgba(0, 0, 0, 0) 70%);
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
                    {}
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

            {{-- Enlaces de servicios públicos de la portada (sidebar fijo a la izquierda). --}}
            {{-- Va fuera de .hero-content: ese div conserva un transform tras la animación --}}
            {{-- fadeUp y rompería el anclaje position: fixed del menú a la ventana. --}}
            <nav class="menu-lateral" aria-label="Servicios de postgrado">
                <a href="{{ route('preinscripcion.create') }}" class="menu-lateral-item">
                    <i class="fa-solid fa-file-pen"></i>
                    <span>Preinscripción</span>
                </a>

                <a href="{{ route('postgrado.index') }}" class="menu-lateral-item">
                    <i class="fa-solid fa-graduation-cap"></i>
                    <span>Ver Postgrados</span>
                </a>

                {{-- Contacto como texto (sin enlace), a pedido de la coordinación --}}
                <div class="menu-lateral-item menu-texto">
                    <i class="fa-solid fa-envelope"></i>
                    <span>Contacto:<br>postgradoca.udo.anzoategui@gmail.com</span>
                </div>

                <button type="button" class="menu-lateral-item" id="btnModalInfo"
                    style="cursor: pointer; font-family: inherit; width: 100%;">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>Aranceles</span>
                </button>
            </nav>
        </section>


    </main>

    <div class="bottom-copyright">
        <p>&copy; 2026 PostUDO - Todos los derechos reservados.</p>
    </div>

    {{-- Modal «Aranceles»: contenido dinámico. Lo actualiza el administrador --}}
    {{-- desde /administrador/aranceles (texto crudo en aranceles.json); el     --}}
    {{-- parseo a este formato visual lo hace ArancelesService al renderizar.   --}}
    @php
        $servicioAranceles = app(\App\Services\ArancelesService::class);
        $aranceles = $servicioAranceles->parsear($servicioAranceles->obtenerTexto());
    @endphp
    <div class="modal-info-fondo" id="modalInfo">
        <div class="modal-info-caja">
            <div class="modal-info-cabecera">
                <h3><i class="fa-solid fa-circle-info" style="color: var(--accent);"></i> Aranceles de Postgrado</h3>
            </div>

            <div class="modal-info-cuerpo">
                {{-- Introducción: los párrafos previos al primer grupo --}}
                @foreach ($aranceles['intro'] as $parrafo)
                    <p class="modal-info-intro">{{ $parrafo }}</p>
                @endforeach

                {{-- Aviso destacado de la tasa de cambio (si aparece en el texto) --}}
                @if ($aranceles['aviso'])
                    <div class="modal-info-aviso">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                        <span>{{ $aranceles['aviso'] }}</span>
                    </div>
                @endif

                {{-- Grupos: título (con icono por palabras clave) + filas concepto/precio --}}
                @foreach ($aranceles['grupos'] as $grupo)
                    @if ($grupo['titulo'] !== '')
                        <h4 class="modal-info-grupo">
                            <i class="fa-solid {{ $servicioAranceles->iconoDeGrupo($grupo['titulo']) }}"></i>
                            {{ $grupo['titulo'] }}
                        </h4>
                    @endif

                    {{-- Párrafos sueltos dentro del grupo (si los hubiera) --}}
                    @foreach ($grupo['notas'] as $nota)
                        <p class="modal-info-nota">{{ $nota }}</p>
                    @endforeach

                    @if (count($grupo['items']))
                        <ul class="modal-info-lista">
                            @foreach ($grupo['items'] as $item)
                                <li class="modal-info-fila">
                                    <span>{{ $item['concepto'] }}</span>
                                    @if ($item['precio'] !== '')
                                        <span class="modal-info-precio">{{ $item['precio'] }}</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endforeach
            </div>

            <div class="modal-info-pie">
                <button type="button" class="modal-info-cerrar" id="btnCerrarModalInfo">Cerrar</button>
            </div>
        </div>
    </div>

    <script>
        // Apertura y cierre del modal «Aranceles» (el contenido lo pinta
        // el servidor desde aranceles.json; esto solo maneja el display).
        const modalInfo = document.getElementById('modalInfo');
        document.getElementById('btnModalInfo').addEventListener('click', function () {
            modalInfo.style.display = 'flex';
        });
        document.getElementById('btnCerrarModalInfo').addEventListener('click', function () {
            modalInfo.style.display = 'none';
        });
        modalInfo.addEventListener('click', function (evento) {
            if (evento.target === modalInfo) {
                modalInfo.style.display = 'none';
            }
        });
    </script>

</body>

</html>