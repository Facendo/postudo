<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{asset('iconopagina.png')}}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <title>PostUDO || Inicio</title>
    <style>
        /* --- General Body Styles --- */
        body {
            font-family: 'Inter', sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            /* background-color: #f8f9fa; */
            /* Nuevo: Fondo sutil para el cuerpo para un look más moderno */
            background: linear-gradient(135deg, #bdd0f0 0%, #1d3070 100%);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* --- Global Section Styling --- */
        .page-content {
            padding-top: 0; /* No padding at the top if nav is fixed */
        }

        .hero-section, .about-us-section, .offerings-section, .why-choose-us-section, .cta-section {
            padding: 100px 40px; /* Generous padding for all major sections */
            max-width: 1400px; /* Max width for content container */
            margin: 0 auto;
            box-sizing: border-box;
        }

        h1, h2, h3 {
            font-family: 'Montserrat', sans-serif;
            color: #ffffff ; /* Dark blue, matching nav */
            margin-bottom: 20px;
            line-height: 1.2;
        }

        h1 {
            font-size: 3.8em;
            font-weight: 700;
        }

        h2 {
            font-size: 2.8em;
            font-weight: 600;
            text-align: center;
        }

        h3 {
            font-size: 1.8em;
            font-weight: 600;
        }

        p {
            font-size: 1.1em;
            color: #555;
            margin-bottom: 1em;
        }

        .section-description {
            font-size: 1.2em;
            color: #666;
            margin-bottom: 50px;
            text-align: center;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        /* --- Navigation Bar Styles --- */
        nav {
            width: 100%;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #bdd0f0 0%, #1d3070 100%);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.3),
                        0px 2px 4px rgba(0, 0, 0, 0.1);
            padding: 0 60px;
            box-sizing: border-box;
            position: relative;
            z-index: 1000;
            border-bottom: linear-gradient(135deg, #bdd0f0 0%, #1d3070 100%);
        }

        .contenedor {
            margin: 0px;
            display: flex;
            align-items: center;
            gap: 35px;
            margin-left: 0;
        }

        .logo {
            width: 60px;
            height: auto;
            margin: 0px;
            filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.1));
        }

        .logo_barra {
            width: 150px;
            height: 150px;
            display: flex;
            border-radius: 100%;
            object-fit: cover;
        }

        .titulo_barra {
            font-family: 'Montserrat', sans-serif;
            font-size: 32px;
            color: #e0e7e9;
            text-align: left;
            flex-grow: 1;
            text-shadow: 0px 2px 6px rgba(0, 0, 0, 0.4);
            margin-left: 20px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        /* Botones de Navegación Mejorados */
        .button_barra {
            margin: 0 20px;
            padding: 12px 22px; /* Reducido ligeramente para más cohesión */
            text-decoration: none;
            color: #a7d9ef; /* Color base más suave */
            font-family: 'Montserrat', sans-serif;
            font-size: 16px; /* Ajuste de tamaño */
            border-radius: 8px; /* Menos redondeado para un look más moderno */
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            font-weight: 500;
            position: relative;
            overflow: hidden;
            background: transparent;
            display: inline-flex; /* Para centrar el texto verticalmente si es necesario */
            align-items: center;
            justify-content: center;
        }

        .button_barra::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px; /* Línea más gruesa */
            background-color: #e67e55;
            transform: scaleX(0); /* Empieza oculto */
            transform-origin: bottom right; /* Crece desde la derecha */
            transition: transform 0.3s ease-out;
        }

        .button_barra:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.15); /* Efecto de hover más pronunciado */
            transform: translateY(-2px); /* Lift sutil */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Sombra al levantar */
            border-color: rgba(255, 255, 255, 0.4); /* Borde más visible al hover */
        }

        .button_barra:hover::before {
            transform: scaleX(1); /* Se expande al 100% */
            transform-origin: bottom left; /* Crece hacia la izquierda */
        }

        .cerrar_sesion {
            background: linear-gradient(to right, #ef233c, #d90429);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            margin-right: 0;
            font-weight: 600;
            letter-spacing: 0.2px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            position: relative; /* Necesario para el efecto de brillo */
        }

        .cerrar_sesion:hover {
            background: linear-gradient(to right, #d90429, #ef233c);
            transform: translateY(-3px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.4);
            /* Animación de brillo */
            animation: shine 1.5s infinite;
        }

        /* Animación de brillo para el botón cerrar_sesion */
        @keyframes shine {
            0% {
                box-shadow: 0 8px 18px rgba(0, 0, 0, 0.4);
            }
            50% {
                box-shadow: 0 0 25px rgba(239, 35, 60, 0.7); /* Brillo rojo */
            }
            100% {
                box-shadow: 0 8px 18px rgba(0, 0, 0, 0.4);
            }
        }


        /* --- Hero Section --- */
        .hero-section {
            position: relative;
            height: 65vh; 
            /* Eliminado: background: url('{{ asset('fondo.jpg') }}') no-repeat center center/cover; */
            /* Nuevo: Degradado suave y vibrante */
            background: linear-gradient(135deg, #bdd0f0 0%, #1d3070 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: rgb(172, 172, 172);
            overflow: hidden;
            border-bottom-left-radius: 20px; /* Esquinas inferiores redondeadas */
            border-bottom-right-radius: 20px; /* Esquinas inferiores redondeadas */
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3); /* Sombra más pronunciada */
            padding-top: 80px;
        }

        .hero-section::before { /* Dark overlay for text readability */
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            /* background: linear-gradient(to bottom, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.4)); */
            /* Nuevo: Overlay más sutil si se usa degradado */
            background: rgba(0, 0, 0, 0.2); /* Oscurecimiento ligero */
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 900px;
            padding: 20px;
            animation: fadeInScale 1.2s ease-out forwards; /* Animación ligeramente más lenta */
        }

        .hero-content h1 {
            color: white;
            font-size: 4.0em; /* Título aún más grande */
            margin-bottom: 25px;
            text-shadow: 3px 3px 8px rgba(0,0,0,0.8);
            letter-spacing: -2px; /* Ajuste para un look más moderno */
        }

        .hero-content p {
            color: #e0e7e9;
            font-size: 1.1em; /* Párrafo más grande */
            margin-bottom: 40px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
            font-weight: 300; /* Ligeramente más delgado */
        }

        .hero-buttons {
            display: flex;
            justify-content: center;
            gap: 25px; /* Más espacio entre botones */
        }

        /* --- Buttons --- */
        .btn {
            display: inline-block;
            padding: 16px 38px; /* Mayor padding para un look más premium */
            border-radius: 50px; /* Pill shape for modern look */
            text-decoration: none;
            font-weight: 600;
            font-size: 1.15em; /* Tamaño de fuente ligeramente mayor */
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); /* Transición global más suave */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15); /* Sombra inicial más suave */
            position: relative;
            overflow: hidden; /* Para el efecto de brillo */
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2); /* Brillo */
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.4s ease-out, height 0.4s ease-out;
            z-index: 0;
        }

        .btn:hover::before {
            width: 300%;
            height: 300%;
        }

        .btn-primary {
            background: linear-gradient(45deg, #e67e55, #fc4700); 
            color: white;
            border: none; /* Sin borde para un look más limpio */
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #fc4700, #e67e55); /* Invertir degradado al hover */
            transform: translateY(-7px); /* Lift más pronunciado */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3); /* Sombra más fuerte */
            animation: none; /* Deshabilitar animación de pulso si hay otra */
        }

        .btn-secondary {
            background: linear-gradient(45deg, #e67e55, #fc4700); /* Degradado azul */
            color: white;
            border: none; /* Sin borde para un look más limpio */
        }

        .btn-secondary:hover {
            background-color: #e67e55; /* Fondo más visible al hover */
            transform: translateY(-7px); /* Lift más pronunciado */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3); /* Sombra más fuerte */
            animation: none; /* Deshabilitar animación de pulso si hay otra */
        }

        /* --- Eliminar animación de pulso, ya que el nuevo hover es más dinámico --- */
        /* @keyframes pulse-button */
        /* Eliminada la regla @keyframes pulse-button */

        /* --- About Us Section --- */
        .about-us-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 80px; /* More space for a clean layout */
            text-align: left;
            background: linear-gradient(135deg, #bdd0f0 0%, #1d3070 100%);
            border-radius: 15px; /* Subtle rounded corners for the section */
            box-shadow: 0 10px 30px rgba(0,0,0,0.08); /* Sombra ligeramente más fuerte */
            margin-top: 80px; /* Más margen superior */
            margin-bottom: 80px; /* Más margen inferior */
            padding: 80px; /* Adjusted padding for internal content */
            
        }   

        .about-text-content {
            flex: 2; /* Text takes more space */
            padding-right: 30px;
        }

        .about-text-content h2 {
            text-align: left; /* Align title to left */
            font-size: 2.8em;
            margin-bottom: 25px;
            color: #ffffff;
        }

        .about-text-content p {
            font-size: 1.15em;
            line-height: 1.8;
            color: #414141;
        }

        .about-image-container {
            flex: 1; /* Image takes less space */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .about-image {
            max-width: 100%;
            height: auto;
            border-radius: 20px; /* More rounded corners */
            box-shadow: 0 15px 30px rgba(0,0,0,0.2); /* Sombra más pronunciada */
            object-fit: cover;
            filter: grayscale(10%); /* Efecto sutil para que la imagen se integre mejor */
            transition: filter 0.3s ease-in-out;
        }

        .about-image:hover {
            filter: grayscale(0%); /* Color completo al pasar el ratón */
        }

        /* --- Our Offerings Section (Tarjetas Mejoradas) --- */
        .offerings-section {
            background: linear-gradient(135deg, #bdd0f0 0%, #1d3070 100%); /* Light blue background */
            padding-top: 100px;
            padding-bottom: 100px;
        }

        .program-cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 40px; /* Increased gap */
            margin-top: 60px;
        }

        .program-card {
            background: linear-gradient(135deg, #ffffff 70%, #ffffff  100%);
            padding: 40px;
            border-radius: 20px; /* Más redondeado */
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); /* Sombra más suave inicialmente */
            transition: transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1), box-shadow 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            text-align: center;
            border: 1px solid rgba(0, 0, 0, 0.08); /* Borde sutil */
            display: flex; /* Flexbox para contenido interno */
            flex-direction: column;
            justify-content: space-between; /* Para empujar el link al final */
        }

        .program-card:hover {
            transform: translateY(-18px); /* Lift más pronunciado */
            box-shadow: 0 25px 50px rgba(0,0,0,0.25); /* Sombra más intensa al hover */
            border-color: #007bff; /* Highlight border on hover */
        }

        .program-card h3 {
            color: #e67e55; /* Dark blue */
            font-size: 2.2em; /* Título de tarjeta más grande */
            margin-bottom: 15px;
        }

        .program-card p {
            font-size: 1.05em;
            color: #666;
            margin-bottom: 25px;
            flex-grow: 1; /* Permite que el párrafo crezca y ocupe espacio */
        }

        .card-link {
            display: inline-block;
            margin-top: auto; /* Empuja el link hacia abajo */
            color: #007bff;
            text-decoration: none;
            font-weight: 700;
            position: relative;
            padding-bottom: 5px;
            font-size: 1.1em; /* Link de tarjeta ligeramente más grande */
        }

        .card-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 3px; /* Línea más gruesa */
            background-color: #007bff;
            transform: scaleX(0);
            transform-origin: bottom right;
            transition: transform 0.3s ease-out;
        }

        .card-link:hover::after {
            transform: scaleX(1);
            transform-origin: bottom left;
        }


        /* --- Call to Action Section --- */
        .cta-section {
            background: linear-gradient(135deg, #bdd0f0 0%, #1d3070 100%);   /* Dark blue gradient */
            color: white;
            padding: 120px 40px;
            text-align: center;
            border-radius: 20px; /* Más redondeado para la sección */
            margin: 80px auto; /* Más margen, centrado */
            box-shadow: 0 20px 50px rgba(0,0,0,0.4); /* Sombra más fuerte */
        }

        .cta-content h2 {
            color: white;
            font-size: 3.5em; /* Título de CTA más grande */
            margin-bottom: 25px;
            letter-spacing: -0.5px;
        }

        .cta-content p {
            color: #e0e7e9;
            font-size: 1.4em; /* Párrafo más grande */
            margin-bottom: 50px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-cta {
            background: linear-gradient(45deg, #28a745, #218838); /* Degradado verde */
            color: white;
            font-size: 1.4em; /* Botón CTA más grande */
            padding: 20px 50px; /* Mayor padding */
            border-radius: 50px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25); /* Sombra inicial */
            border: none;
        }

        .btn-cta:hover {
            background: linear-gradient(45deg, #218838, #28a745); /* Invertir degradado al hover */
            transform: translateY(-8px); /* Lift más pronunciado */
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4); /* Sombra más fuerte */
        }

        /* --- Custom Alert Styles (from your original code) --- */
        .my-alert-style {
            background-color: #d4edda; /* Light green */
            color: #155724; /* Dark green text */
            border: 1px solid #c3e6cb;
            border-radius: 0.25rem;
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        .my-red-alert-style {
            background-color: #f8d7da; /* Light red */
            color: #721c24; /* Dark red text */
            border: 1px solid #f5c6cb;
            border-radius: 0.25rem;
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .block {
            display: block;
        }

        .sm\:inline {
            display: inline;
        }

        /* --- Animations --- */
        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Animación para el botón "Cerrar Sesión" */
        @keyframes shine-button {
            0% {
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
            }
            50% {
                box-shadow: 0 0 20px rgba(239, 35, 60, 0.7); /* Brillo rojo */
            }
            100% {
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
            }
        }

        /* --- Footer Styles (Sophisticated) --- */
        #pie {
            background-color: #0a1128; /* Dark blue, matching nav */
            color: #a7d9ef; /* Light blue text */
            padding: 40px 60px;
            text-align: center;
            font-family: 'Inter', sans-serif;
            font-size: 1em;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.2);
        }

        .contenido_pie p {
            margin: 0;
            color: inherit; /* Inherit color from parent */
            font-size: 1.05em;
        }

        /* --- Responsive Design --- */
        @media (max-width: 1024px) {
            nav {
                padding: 0 30px;
                height: 80px;
            }

            .titulo_barra {
                font-size: 26px;
                margin-left: 10px;
            }

            .button_barra {
                margin: 0 10px;
                padding: 10px 18px;
                font-size: 15px; /* Ligeramente más pequeño */
            }

            .hero-content h1 {
                font-size: 3.8em; /* Ajustado */
            }

            .hero-content p {
                font-size: 1.3em; /* Ajustado */
            }

            .about-us-section {
                flex-direction: column;
                gap: 40px;
                padding: 60px 30px;
            }

            .about-text-content, .about-image-container {
                flex: none;
                width: 100%;
                padding-right: 0;
            }

            .about-text-content h2 {
                text-align: center;
            }

            .program-cards-container, .benefits-grid {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 30px;
            }

            h2 {
                font-size: 2.5em; /* Ajustado */
            }

            .cta-content h2 {
                font-size: 2.8em; /* Ajustado */
            }
        }

        @media (max-width: 768px) {
            nav {
                padding: 0 20px;
                height: 70px;
            }

            .logo {
                width: 50px;
            }

            .titulo_barra {
                font-size: 22px;
                margin-left: 5px;
            }

            .button_barra {
                font-size: 14px;
                padding: 8px 15px;
                margin: 0 5px;
            }

            .cerrar_sesion {
                font-size: 14px;
                padding: 8px 15px;
            }

            .hero-section {
                height: 70vh; /* Ligeramente más pequeño */
                padding: 80px 20px;
            }

            .hero-content h1 {
                font-size: 3.2em; /* Ajustado */
            }

            .hero-content p {
                font-size: 1.1em; /* Ajustado */
            }

            .hero-buttons {
                flex-direction: column;
                gap: 15px;
            }

            .btn {
                padding: 12px 25px;
                font-size: 1.05em; /* Ajustado */
                width: 80%; /* Make buttons full width on small screens */
                max-width: 300px;
            }

            .about-us-section, .offerings-section, .why-choose-us-section, .cta-section {
                padding: 60px 20px;
            }

            h2 {
                font-size: 2.2em;
            }

            .section-description {
                font-size: 1em;
                margin-bottom: 30px;
            }

            .program-cards-container, .benefits-grid {
                grid-template-columns: 1fr; /* Single column on very small screens */
                gap: 25px;
            }

            .program-card, .benefit-item {
                padding: 30px;
            }

            .cta-content h2 {
                font-size: 2.3em; /* Ajustado */
            }

            .cta-content p {
                font-size: 1.2em; /* Ajustado */
                margin-bottom: 40px;
            }

            .btn-cta {
                font-size: 1.1em;
                padding: 15px 30px;
            }
        }

        @media (max-width: 480px) {
            nav {
                padding: 0 10px;
                height: 60px;
            }

            .logo {
                width: 40px;
                margin-right: 5px;
            }

            .titulo_barra {
                font-size: 18px;
            }

            .button_barra {
                display: none; /* Hide nav links on very small screens, consider a hamburger menu */
            }

            .cerrar_sesion {
                font-size: 13px;
                padding: 6px 12px;
            }

            .hero-section {
                height: 60vh; /* Aún más pequeño */
                padding: 50px 15px;
            }

            .hero-content h1 {
                font-size: 2.5em; /* Ajustado */
            }

            .hero-content p {
                font-size: 0.95em; /* Ajustado */
            }

            .about-us-section, .offerings-section, .why-choose-us-section, .cta-section {
                padding: 40px 15px;
            }

            h2 {
                font-size: 1.8em;
            }

            .program-card h3 {
                font-size: 1.6em;
            }

            .benefit-item h3 {
                font-size: 1.4em;
            }

            .btn-cta {
                font-size: 1em;
                padding: 12px 25px;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="contenedor">
                <img src="{{ asset('../Logo_UDO.png') }}" alt="PostUDO Logo" class="logo">
                <h1 class="titulo_barra">PostUDO</h1>
            </div>

            <div>
                <a href="/postgrado" class="button_barra">Programas</a>
                @auth
                    <a href="{{ url('/perfil') }}" class="button_barra">Perfil</a>
                    
                    <form action="{{ url('/logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="cerrar_sesion">Cerrar Sesión</button>
                    </form>
                @endauth
                @guest
                    <a href="{{ url('/login') }}" class="button_barra">Iniciar Sesión</a>
                    <a href="{{ url('/register') }}" class="button_barra">Registrarse</a>
                @endguest
                
            </div>
        </nav>
    </header>

    <main>
        <div class="flash-messages">
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

        <div class="page-content">
            <div class="hero-section">
                <div class="hero-content">
                    <h1>Bienvenidos a PostUDO</h1>
                    <p>Tu plataforma integral para acceder a posgrados de vanguardia y especialización que transformarán tu carrera.</p>
                    <div class="hero-buttons">
                        <a href="#about-us" class="btn btn-primary">Conoce Más</a>
                        <a href="/postgrado" class="btn btn-secondary">Explora Programas</a>
                    </div>
                </div>
                </div>

            <div id="about-us" class="about-us-section">
                <div class="about-text-content">
                    <h2>Nuestra Misión: Impulsar Tu Éxito</h2>
                    <p>
                        En PostUDO, estamos comprometidos con tu crecimiento profesional y académico. Hemos diseñado esta plataforma pensando en ti: el profesional ambicioso que busca trascender, el investigador apasionado en pos de nuevos horizontes, o el recién graduado listo para dar el siguiente gran paso en su carrera.
                    </p>
                    <p>
                        Creemos que la especialización y la formación continua son pilares fundamentales para el éxito en un entorno profesional en constante evolución. Por eso, te ofrecemos acceso a programas de posgrado de alta calidad que te equiparán con las herramientas y el conocimiento para liderar en tu campo.
                    </p>
                </div>
                <div class="about-image-container">
                    <img src="{{ asset('Profesionales_investigando.jpg') }}" alt="Profesionales investigando" class="about-image">
                </div>
            </div>

            <div id="offerings" class="offerings-section">
                <h2>Nuestra Oferta Académica</h2>
                <p class="section-description">Descubre programas de posgrado diseñados para las demandas del mercado actual y futuro.</p>
                <div class="program-cards-container">
                    <div class="program-card">
                        <h3>Maestrías</h3>
                        <p>Consolida tus conocimientos y adquiere habilidades avanzadas en diversas áreas. Perfectas para la especialización.</p>
                        <a href="{{ url('/postgrado') }}" class="card-link">Ver Maestrías &rarr;</a>
                    </div>
                    <div class="program-card">
                        <h3>Doctorados</h3>
                        <p>Impulsa la frontera del saber y conviértete en un investigador líder. Ideal para la innovación y la academia.</p>
                        <a href="{{ url('/postgrado') }}" class="card-link">Ver Doctorados &rarr;</a>
                    </div>
                    <div class="program-card">
                        <h3>Diplomados y Especializaciones</h3>
                        <p>Formación intensiva y específica para potenciar habilidades clave en corto tiempo. Actualízate rápidamente.</p>
                        <a href="{{ url('/postgrado') }}" class="card-link">Ver Diplomados &rarr;</a>
                    </div>
                </div>
            </div>

            <div class="cta-section">
                <h3> 2025 PostUdo. Todos los derechos reservados</h3>
            </div>
