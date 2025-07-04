<x-layout title="PostUDO || Registro de Postgrados">

    <main>
        <section class="content_texto_bienvenida">
            <label>Registrar Nuevas Opciones</label>
        </section>

        <section class="action-buttons-container">
            <button id="btnRegisterArea" class="button_body">
                Registrar Área
            </button>
            <button id="btnRegisterCarrera" class="button_body">
                Registrar Carrera
            </button>
            <button id="btnRegisterEspecialidad" class="button_body">
                Registrar Especialidad
            </button>
        </section>

        <section id="formArea" class="info-section register-form-section">
            <h3>Registro de Nueva Área</h3>
            <form class="registration-form">
                <div class="form-group">
                    <label for="area_name" class="form-label">Nombre del Área:</label>
                    <input type="text" id="area_name" name="name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="area_description" class="form-label">Descripción del Área:</label>
                    <textarea id="area_description" name="description" class="form-input" rows="4"></textarea>
                </div>
                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Registrar Área</button>
                </div>
            </form>
        </section>

        <section id="formCarrera" class="info-section register-form-section">
            <h3>Registro de Nueva Carrera</h3>
            <form class="registration-form">
                <div class="form-group">
                    <label for="carrera_id" class="form-label">ID de Carrera:</label>
                    <input type="text" id="carrera_id" name="id_carrera" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="carrera_name" class="form-label">Nombre:</label>
                    <input type="text" id="carrera_name" name="nombre" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="carrera_codigo_especialidad" class="form-label">Código Especialidad:</label>
                    <input type="text" id="carrera_codigo_especialidad" name="codigo_especialidad" class="form-input" required>
                </div>

                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Registrar Carrera</button>
                </div>
            </form>
        </section>

         <section id="formEspecialidad" class="info-section register-form-section">
            <h3>Registro de Nueva Especialidad</h3>
            <form class="registration-form">
                <div class="form-group">
                    <label for="especialidad_name" class="form-label">Nombre:</label>
                    <input type="text" id="especialidad_name" name="nombre" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="especialidad_codigo" class="form-label">Código Especialidad:</label>
                    <input type="text" id="especialidad_codigo" name="codigo_especialidad" class="form-input" required>
                </div>
            
                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Registrar Especialidad</button>
                </div>
            </form>
        </section>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Obtener referencias a los botones
            const btnRegisterArea = document.getElementById('btnRegisterArea');
            const btnRegisterCarrera = document.getElementById('btnRegisterCarrera');
            const btnRegisterEspecialidad = document.getElementById('btnRegisterEspecialidad');

            // Obtener referencias a las secciones de formulario
            const formArea = document.getElementById('formArea');
            const formCarrera = document.getElementById('formCarrera');
            const formEspecialidad = document.getElementById('formEspecialidad');

            // Agrupar todas las secciones de formulario
            const formSections = [formArea, formCarrera, formEspecialidad];

            /**
             * Oculta todas las secciones de formulario.
             */
            function hideAllFormSections() {
                formSections.forEach(section => {
                    section.classList.remove('active');
                });
            }

            // AñadirEventListeners a los botones
            if (btnRegisterArea) {
                btnRegisterArea.addEventListener('click', () => {
                    hideAllFormSections();
                    formArea.classList.add('active'); // Muestra solo el formulario de Área
                });
            }

            if (btnRegisterCarrera) {
                btnRegisterCarrera.addEventListener('click', () => {
                    hideAllFormSections();
                    formCarrera.classList.add('active'); // Muestra solo el formulario de Carrera
                });
            }

            if (btnRegisterEspecialidad) {
                btnRegisterEspecialidad.addEventListener('click', () => {
                    hideAllFormSections();
                    formEspecialidad.classList.add('active'); // Muestra solo el formulario de Especialidad
                });
            }
        });
    </script>
</x-layout>