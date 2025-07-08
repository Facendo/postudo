<x-layout title="PostUDO || Gestión de Postgrados">
    {{-- Script para los iconos de Font Awesome (si no lo tienes ya en tu layout) --}}
    {{-- Asegúrate de que 'your-font-awesome-kit-code.js' sea tu propio código de kit --}}
    <script src="https://kit.fontawesome.com/your-font-awesome-kit-code.js" crossorigin="anonymous"></script>

    {{-- Pasa los datos desde Laravel al JavaScript --}}
    <script>
        window.initialAreas = @json($area);
        window.initialCarreras = @json($carrera);
        window.initialEspecialidades = @json($especialidad);
    </script>
    {{-- Incluye tu archivo JavaScript externo --}}
    

    <section class="content_texto_bienvenida">
        <label>Gestionar Opciones de Postgrados</label>
    </section>

    <section class="action-buttons-container">
        <button id="btnViewAreas" class="button_body active">
            Ver Áreas
        </button>
        <button id="btnViewCarreras" class="button_body">
            Ver Carreras
        </button>
    </section>

    {{-- SECCIÓN PRINCIPAL PARA LISTAR ÁREAS --}}
    <section id="listViewAreas" class="info-section active"> {{-- Activa por defecto --}}
        <h3>Áreas Registradas</h3>
        <div class="list-actions" style="display: flex; justify-content: flex-end; margin-bottom: 15px;">
            <button id="btnNewArea" class="button_body">
                Registrar Nueva Área
            </button>
        </div>
        <div id="areasList" class="data-list-container">
            {{-- Aquí se cargarán las áreas dinámicamente --}}
            <p>Cargando áreas...</p>
        </div>
        <div class="form-actions" style="justify-content: center; margin-top: 20px;">
            {{-- No hay botón de submit aquí, ya que es una vista de lista --}}
        </div>
    </section>

    {{-- SECCIÓN PRINCIPAL PARA LISTAR CARRERAS --}}
    <section id="listViewCarreras" class="info-section">
        <h3>Carreras Registradas</h3>
        <div class="list-actions" style="display: flex; justify-content: flex-end; margin-bottom: 15px;">
            <button id="btnNewCarrera" class="button_body">
                Registrar Nueva Carrera
            </button>
        </div>
        <div id="carrerasList" class="data-list-container">
            {{-- Aquí se cargarán las carreras dinámicamente --}}
            <p>Cargando carreras...</p>
        </div>
        <div class="form-actions" style="justify-content: center; margin-top: 20px;">
            {{-- No hay botón de submit aquí --}}
        </div>
    </section>

    {{-- SECCIÓN DE DETALLES DE ÁREA (Inicialmente oculta) --}}
    <section id="areaDetailsSection" class="info-section detail-section">
        <div class="detail-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 id="areaDetailName">Detalles del Área: [Nombre del Área]</h3>
            <button id="btnBackToAreas" class="button_body small-button">
                <i class="fas fa-arrow-left"></i> Volver a Áreas
            </button>
        </div>
        <div class="detail-content">
            <h4>Descripción:</h4>
            <p id="areaDetailDescription">Cargando descripción...</p>

            <h4>Carreras asociadas:</h4>
            <div id="areaCarrerasList" class="data-list-container nested-list">
                {{-- Aquí se cargarán las carreras de esta área --}}
                <p>Cargando carreras asociadas...</p>
            </div>
        </div>
    </section>

    {{-- SECCIÓN DE DETALLES DE CARRERA (Inicialmente oculta) --}}
    <section id="carreraDetailsSection" class="info-section detail-section">
        <div class="detail-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 id="carreraDetailName">Detalles de la Carrera: [Nombre de la Carrera]</h3>
            <button id="btnBackToCarreras" class="button_body small-button">
                <i class="fas fa-arrow-left"></i> Volver a Carreras
            </button>
        </div>
        <div class="detail-content">
            <h4>Código de Carrera:</h4> {{-- Cambiado de "Código" para ser más específico --}}
            <p id="carreraDetailId">Cargando código...</p>

            <h4>Especialidades asociadas:</h4>
            <div class="list-actions" style="display: flex; justify-content: flex-end; margin-bottom: 15px;">
                <button id="btnNewEspecialidad" class="button_body">
                    Registrar Nueva Especialidad
                </button>
            </div>
            <div id="carreraEspecialidadesList" class="data-list-container nested-list">
                {{-- Aquí se cargarán las especialidades de esta carrera --}}
                <p>Cargando especialidades asociadas...</p>
            </div>
        </div>
    </section>

    {{-- SECCIÓN PARA REGISTRAR NUEVA ÁREA (Inicialmente oculta) --}}
    <section id="formArea" class="info-section register-form-section">
        <h3>Registrar Nueva Área</h3>
        <form class="registration-form" action="{{ route('administrador.area.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="area_name_new" class="form-label">Codigo del area:</label>
                <input type="text" id="codigo" name="codigo" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="area_name_new" class="form-label">Nombre del Área:</label>
                <input type="text" id="nombre" name="nombre" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="area_description_new" class="form-label">Descripción del Área:</label>
                <textarea id="area_description_new" name="descripcion" class="form-input" rows="4"></textarea>
            </div>
            <div class="form-actions" style="justify-content: center;">
                <button type="submit" class="submit-button">Guardar Área</button>
                <button type="button" id="btnCancelArea" class="button_body cancel-button">Cancelar</button>
            </div>
        </form>
    </section>

    {{-- SECCIÓN PARA REGISTRAR NUEVA CARRERA (Inicialmente oculta) --}}
    <section id="formCarrera" class="info-section register-form-section">
        <h3>Registrar Nueva Carrera</h3>
        <form class="registration-form" action="{{route('administrador.carrera.store')}}" method="POST">
            @csrf
            <div class="form-group">
                <label for="carrera_id_new" class="form-label">ID de Carrera (Código único):</label> {{-- Más descriptivo --}}
                <input type="text" id="carrera_id_new" name="id_carrera" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="carrera_name_new" class="form-label">Nombre de la Carrera:</label>
                <input type="text" id="carrera_name_new" name="nombre" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="carrera_area_id_new" class="form-label">Área a la que pertenece:</label>
                <select id="carrera_area_id_new" name="id_area" class="form-input" required>
                    <option value="">Seleccione un Área</option>
                </select>
            </div>

            <div class="form-actions" style="justify-content: center;">
                <button type="submit" class="submit-button">Guardar Carrera</button>
                <button type="button" id="btnCancelCarrera" class="button_body cancel-button">Cancelar</button>
            </div>
        </form>
    </section>

    {{-- SECCIÓN PARA REGISTRAR NUEVA ESPECIALIDAD (Inicialmente oculta) --}}
    <section id="formEspecialidad" class="info-section register-form-section">
        <h3>Registrar Nueva Especialidad</h3>
        <form class="registration-form" action="{{route('administrador.especialidad.store')}}" method="POST">
            @csrf
            <div class="form-group">
                <label for="especialidad_name_new" class="form-label">Nombre de la Especialidad:</label>
                <input type="text" id="especialidad_name_new" name="nombre" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="especialidad_codigo_new" class="form-label">Código Único de la Especialidad:</label>
                <input type="text" id="especialidad_codigo_new" name="codigo" class="form-input" required> {{-- Asumiendo que el campo es 'codigo' en tu modelo Especialidad --}}
            </div>
             <div class="form-group">
                <label for="especialidad_carrera_id_new" class="form-label">Carrera a la que pertenece:</label>
                <select id="especialidad_carrera_id_new" name="carrera_id" class="form-input" required>
                    <option value="">Seleccione una Carrera</option>
                    {{-- Opciones de carreras se cargarán dinámicamente con JavaScript --}}
                </select>
            </div>
            <div class="form-actions" style="justify-content: center;">
                <button type="submit" class="submit-button">Guardar Especialidad</button>
                <button type="button" id="btnCancelEspecialidad" class="button_body cancel-button">Cancelar</button>
            </div>
        </form>
    </section>


    {{-- Script para los iconos de Font Awesome (si no lo tienes ya en tu layout) --}}
   
</x-layout>