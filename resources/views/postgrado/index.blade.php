<x-layout title="PostUDO || Postgrado">
<body>

    <main>
        <section class="content_texto_bienvenida">
            <label>Bienvenido al Catálogo de Postgrados UDO</label>
        </section>

        <section class="action-buttons-container">
            <button id="btnArea" class="button_body active-button">
                Áreas de Postgrado
            </button>
            <button id="btnCarrera" class="button_body hidden">
                Carreras por Área
            </button>
            <button id="btnEspecialidad" class="button_body hidden">
                Especialidades por Carrera
            </button>
        </section>

        <section id="infoArea" class="info-section active">
            <h3>Áreas de Postgrado de la UDO</h3>
            <ul class="info-list">
                <li><a href="#" class="area-link" data-target="ingenieria">Ciencias de la Ingeniería</a></li>
                <li><a href="#" class="area-link" data-target="salud">Ciencias de la Salud</a></li>
                <li><a href="#" class="area-link" data-target="administrativas">Ciencias Administrativas y Gerenciales</a></li>
                <li><a href="#" class="area-link" data-target="basicas">Ciencias Básicas</a></li>
                <li><a href="#" class="area-link" data-target="humanidades">Humanidades y Educación</a></li>
            </ul>
        </section>

        <section id="infoCarrera" class="info-section">
            <h3>Carreras por Área</h3>
            <ul class="info-list">
                <li id="ingenieria">
                    <a href="#" class="area-return-link" data-area-name="Ciencias de la Ingeniería">Ciencias de la Ingeniería</a>
                    <ul class="info-list-nested">
                        <li><a href="#" class="carrera-link" data-target="maestria-ingenieria-civil">Maestría en Ingeniería Civil</a></li>
                        <li><a href="#" class="carrera-link" data-target="doctorado-ingenieria-quimica">Doctorado en Ingeniería Química</a></li>
                        <li><a href="#" class="carrera-link" data-target="especializacion-automatizacion">Especialización en Automatización Industrial</a></li>
                    </ul>
                </li>
                <li id="salud">
                    <a href="#" class="area-return-link" data-area-name="Ciencias de la Salud">Ciencias de la Salud</a>
                    <ul class="info-list-nested">
                        <li><a href="#" class="carrera-link" data-target="maestria-salud-publica">Maestría en Salud Pública</a></li>
                        <li><a href="#" class="carrera-link" data-target="especializacion-pediatria">Especialización en Pediatría</a></li>
                        <li><a href="#" class="carrera-link" data-target="doctorado-ciencias-biomedicas">Doctorado en Ciencias Biomédicas</a></li>
                    </ul>
                </li>
                <li id="administrativas">
                    <a href="#" class="area-return-link" data-area-name="Ciencias Administrativas y Gerenciales">Ciencias Administrativas y Gerenciales</a>
                    <ul class="info-list-nested">
                        <li><a href="#" class="carrera-link" data-target="maestria-gerencia-proyectos">Maestría en Gerencia de Proyectos</a></li>
                        <li><a href="#" class="carrera-link" data-target="especializacion-finanzas">Especialización en Finanzas Corporativas</a></li>
                    </ul>
                </li>
                <li id="basicas">
                    <a href="#" class="area-return-link" data-area-name="Ciencias Básicas">Ciencias Básicas</a>
                    <ul class="info-list-nested">
                        <li><a href="#" class="carrera-link" data-target="licenciatura-fisica">Licenciatura en Física</a></li>
                        <li><a href="#" class="carrera-link" data-target="maestria-matematicas">Maestría en Matemáticas</a></li>
                    </ul>
                </li>
                <li id="humanidades">
                    <a href="#" class="area-return-link" data-area-name="Humanidades y Educación">Humanidades y Educación</a>
                    <ul class="info-list-nested">
                        <li><a href="#" class="carrera-link" data-target="maestria-educacion">Maestría en Educación</a></li>
                        <li><a href="#" class="carrera-link" data-target="doctorado-literatura">Doctorado en Literatura</a></li>
                    </ul>
                </li>
            </ul>
        </section>

        <section id="infoEspecialidad" class="info-section">
            <h3>Especialidades por Carrera</h3>
            <ul class="info-list">
                <li id="maestria-ingenieria-civil">
                    <a href="#" class="especialidad-return-link" data-target="ingenieria">Maestría en Ingeniería Civil</a>
                    <ul class="info-list-nested">
                        <li>Estructuras</li>
                        <li>Hidráulica</li>
                        <li>Geotecnia</li>
                    </ul>
                </li>
                <li id="maestria-salud-publica">
                    <a href="#" class="especialidad-return-link" data-target="salud">Maestría en Salud Pública</a>
                    <ul class="info-list-nested">
                        <li>Epidemiología</li>
                        <li>Gestión Hospitalaria</li>
                    </ul>
                </li>
                <li id="especializacion-pediatria">
                    <a href="#" class="especialidad-return-link" data-target="salud">Especialización en Pediatría</a>
                    <ul class="info-list-nested">
                        <li>Neonatología</li>
                        <li>Cardiología Pediátrica</li>
                    </ul>
                </li>
                <li id="doctorado-ingenieria-quimica">
                    <a href="#" class="especialidad-return-link" data-target="ingenieria">Doctorado en Ingeniería Química</a>
                    <ul class="info-list-nested">
                        <li>Catálisis</li>
                        <li>Simulación de Procesos</li>
                    </ul>
                </li>
                <li id="especializacion-automatizacion">
                    <a href="#" class="especialidad-return-link" data-target="ingenieria">Especialización en Automatización Industrial</a>
                    <ul class="info-list-nested">
                        <li>Robótica</li>
                        <li>Control de Procesos</li>
                    </ul>
                </li>
                 <li id="doctorado-ciencias-biomedicas">
                    <a href="#" class="especialidad-return-link" data-target="salud">Doctorado en Ciencias Biomédicas</a>
                    <ul class="info-list-nested">
                        <li>Neurociencias</li>
                        <li>Genética Molecular</li>
                    </ul>
                </li>
                <li id="maestria-gerencia-proyectos">
                    <a href="#" class="especialidad-return-link" data-target="administrativas">Maestría en Gerencia de Proyectos</a>
                    <ul class="info-list-nested">
                        <li>Gestión de Riesgos</li>
                        <li>Planificación Estratégica</li>
                    </ul>
                </li>
                <li id="especializacion-finanzas">
                    <a href="#" class="especialidad-return-link" data-target="administrativas">Especialización en Finanzas Corporativas</a>
                    <ul class="info-list-nested">
                        <li>Mercados de Capitales</li>
                        <li>Análisis de Inversiones</li>
                    </ul>
                </li>
                 <li id="licenciatura-fisica">
                    <a href="#" class="especialidad-return-link" data-target="basicas">Licenciatura en Física</a>
                    <ul class="info-list-nested">
                        <li>Física Cuántica</li>
                        <li>Astrofísica</li>
                    </ul>
                </li>
                 <li id="maestria-matematicas">
                    <a href="#" class="especialidad-return-link" data-target="basicas">Maestría en Matemáticas</a>
                    <ul class="info-list-nested">
                        <li>Álgebra Avanzada</li>
                        <li>Análisis Numérico</li>
                    </ul>
                </li>
                 <li id="maestria-educacion">
                    <a href="#" class="especialidad-return-link" data-target="humanidades">Maestría en Educación</a>
                    <ul class="info-list-nested">
                        <li>Didáctica Universitaria</li>
                        <li>Currículo y Evaluación</li>
                    </ul>
                </li>
                 <li id="doctorado-literatura">
                    <a href="#" class="especialidad-return-link" data-target="humanidades">Doctorado en Literatura</a>
                    <ul class="info-list-nested">
                        <li>Literatura Latinoamericana</li>
                        <li>Crítica Literaria</li>
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
            const actionButtons = [btnArea, btnCarrera, btnEspecialidad];

            // Inicialmente muestra la sección de Áreas
            infoArea.classList.add('active');
            btnArea.classList.add('active-button');

            function hideAllInfoSections() {
                infoSections.forEach(section => {
                    section.classList.remove('active');
                });
            }

            function deactivateAllButtons() {
                actionButtons.forEach(button => {
                    button.classList.remove('active-button');
                });
            }

            function hideAllButtons() {
                actionButtons.forEach(button => {
                    button.classList.add('hidden');
                });
            }

            // Manejo de clics en los botones principales
            btnArea.addEventListener('click', () => {
                hideAllInfoSections();
                deactivateAllButtons();
                hideAllButtons();
                infoArea.classList.add('active');
                btnArea.classList.add('active-button');
                btnArea.classList.remove('hidden');
            });

            btnCarrera.addEventListener('click', () => {
                hideAllInfoSections();
                deactivateAllButtons();
                hideAllButtons(); // Oculta todos los botones
                infoCarrera.classList.add('active');
                btnCarrera.classList.add('active-button');
                btnCarrera.classList.remove('hidden');
                // btnEspecialidad.classList.remove('hidden'); // <-- ESTO SE ELIMINÓ/MODIFICÓ
                btnEspecialidad.classList.add('hidden'); // <-- NUEVO: OCULTAR EN ESTA VISTA
            });

            btnEspecialidad.addEventListener('click', () => {
                hideAllInfoSections();
                deactivateAllButtons();
                hideAllButtons();
                infoEspecialidad.classList.add('active');
                btnEspecialidad.classList.add('active-button');
                btnCarrera.classList.remove('hidden');
                btnEspecialidad.classList.remove('hidden');
            });

            // Manejo de clics en los enlaces de Área (desde la sección de Áreas)
            const areaLinks = document.querySelectorAll('.area-link');
            areaLinks.forEach(link => {
                link.addEventListener('click', (event) => {
                    event.preventDefault();
                    const targetId = event.target.dataset.target;

                    hideAllInfoSections();
                    deactivateAllButtons();

                    infoCarrera.classList.add('active');
                    btnCarrera.classList.add('active-button');

                    btnArea.classList.add('hidden');
                    btnCarrera.classList.remove('hidden');
                    btnEspecialidad.classList.add('hidden'); // <-- NUEVO: OCULTAR EN ESTA VISTA
                    
                    const targetElement = document.getElementById(targetId);
                    if (targetElement) {
                        targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });

            // Manejo de clics en los enlaces de Retorno de Área (desde la sección de Carreras)
            const areaReturnLinks = document.querySelectorAll('.area-return-link');
            areaReturnLinks.forEach(link => {
                link.addEventListener('click', (event) => {
                    event.preventDefault();

                    hideAllInfoSections();
                    deactivateAllButtons();

                    infoArea.classList.add('active');
                    btnArea.classList.add('active-button');

                    btnArea.classList.remove('hidden');
                    btnCarrera.classList.add('hidden');
                    btnEspecialidad.classList.add('hidden');
                });
            });

            // Manejo de clics en los enlaces de Carrera (desde la sección de Carreras a Especialidades)
            const carreraLinks = document.querySelectorAll('.carrera-link');
            carreraLinks.forEach(link => {
                link.addEventListener('click', (event) => {
                    event.preventDefault();
                    const targetId = event.target.dataset.target;

                    hideAllInfoSections();
                    deactivateAllButtons();

                    infoEspecialidad.classList.add('active');
                    btnEspecialidad.classList.add('active-button');

                    btnArea.classList.add('hidden');
                    btnCarrera.classList.add('hidden');
                    btnEspecialidad.classList.remove('hidden');
                    
                    const targetElement = document.getElementById(targetId);
                    if (targetElement) {
                        targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });

            // Manejo de clics en los enlaces de Retorno de Especialidad (desde la sección de Especialidades)
            const especialidadReturnLinks = document.querySelectorAll('.especialidad-return-link');
            especialidadReturnLinks.forEach(link => {
                link.addEventListener('click', (event) => {
                    event.preventDefault();

                    hideAllInfoSections();
                    deactivateAllButtons();

                    infoCarrera.classList.add('active');
                    btnCarrera.classList.add('active-button');

                    btnArea.classList.add('hidden');
                    btnCarrera.classList.remove('hidden');
                    btnEspecialidad.classList.add('hidden'); // <-- NUEVO: OCULTAR EN ESTA VISTA
                    
                    const targetElement = document.getElementById(event.target.closest('li').id);
                    if (targetElement) {
                        targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });
        });
    </script>
</body>
</x-layout>