<x-layout title="PostUDO || Postgrado">
    
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de Postgrado UDO</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&family=Inter:wght@400;500;600&family=Poppins:wght@400;500;600;700&family=Roboto:wght@400;500;700&family=Lato:wght@400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
       <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    </style>
</head>
<body>

    <main>
        <section class="content_texto_bienvenida">
            <label>Bienvenido al Catálogo de Postgrados UDO</label>
        </section>

        <section class="action-buttons-container">
            <button id="btnArea" class="button_body">
                Áreas de Postgrado
            </button>
            <button id="btnCarrera" class="button_body">
                Carreras por Área
            </button>
            <button id="btnEspecialidad" class="button_body">
                Especialidades por Carrera
            </button>
        </section>

        <section id="infoArea" class="info-section">
            <h3>Áreas de Postgrado de la UDO</h3>
            <ul class="info-list">
                <li>Ciencias Básicas</li>
                <li>Ciencias de la Ingeniería</li>
                <li>Ciencias de la Salud</li>
                <li>Ciencias Administrativas y Gerenciales</li>
                <li>Humanidades y Educación</li>
            </ul>
        </section>

        <section id="infoCarrera" class="info-section">
            <h3>Carreras por Área</h3>
            <ul class="info-list">
                <li>
                    Ciencias de la Ingeniería
                    <ul class="info-list-nested">
                        <li>Maestría en Ingeniería Civil</li>
                        <li>Doctorado en Ingeniería Química</li>
                        <li>Especialización en Automatización Industrial</li>
                    </ul>
                </li>
                <li>
                    Ciencias de la Salud
                    <ul class="info-list-nested">
                        <li>Maestría en Salud Pública</li>
                        <li>Especialización en Pediatría</li>
                        <li>Doctorado en Ciencias Biomédicas</li>
                    </ul>
                </li>
                <li>
                    Ciencias Administrativas y Gerenciales
                    <ul class="info-list-nested">
                        <li>Maestría en Gerencia de Proyectos</li>
                        <li>Especialización en Finanzas Corporativas</li>
                    </ul>
                </li>
            </ul>
        </section>

        <section id="infoEspecialidad" class="info-section">
            <h3>Especialidades por Carrera</h3>
            <ul class="info-list">
                <li>
                    Maestría en Ingeniería Civil
                    <ul class="info-list-nested">
                        <li>Estructuras</li>
                        <li>Hidráulica</li>
                        <li>Geotecnia</li>
                    </ul>
                </li>
                <li>
                    Maestría en Salud Pública
                    <ul class="info-list-nested">
                        <li>Epidemiología</li>
                        <li>Gestión Hospitalaria</li>
                    </ul>
                </li>
                <li>
                    Especialización en Pediatría
                    <ul class="info-list-nested">
                        <li>Neonatología</li>
                        <li>Cardiología Pediátrica</li>
                    </ul>
                </li>
            </ul>
        </section>
    </main>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btnArea = document.getElementById('btnArea');
            const btnCarrera = document.getElementById('btnCarrera');
            const btnEspecialidad = document.getElementById('btnEspecialidad');

            const infoArea = document.getElementById('infoArea');
            const infoCarrera = document.getElementById('infoCarrera');
            const infoEspecialidad = document.getElementById('infoEspecialidad');

            const infoSections = [infoArea, infoCarrera, infoEspecialidad];

            function hideAllInfoSections() {
                infoSections.forEach(section => {
                    section.classList.remove('active');
                });
            }

            btnArea.addEventListener('click', () => {
                hideAllInfoSections();
                infoArea.classList.add('active');
            });

            btnCarrera.addEventListener('click', () => {
                hideAllInfoSections();
                infoCarrera.classList.add('active');
            });

            btnEspecialidad.addEventListener('click', () => {
                hideAllInfoSections();
                infoEspecialidad.classList.add('active');
            });
        });
    </script>
</body>
</html>







</x-layout>