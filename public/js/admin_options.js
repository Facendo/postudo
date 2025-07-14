document.addEventListener('DOMContentLoaded', () => {
    // Referencias a los botones principales de vista
    const btnViewAreas = document.getElementById('btnViewAreas');
    const btnViewCarreras = document.getElementById('btnViewCarreras');

    // Referencias a las secciones de lista y detalle
    const listViewAreas = document.getElementById('listViewAreas');
    const listViewCarreras = document.getElementById('listViewCarreras');
    const areaDetailsSection = document.getElementById('areaDetailsSection');
    const carreraDetailsSection = document.getElementById('carreraDetailsSection');

    // Referencias a los formularios de registro
    const formArea = document.getElementById('formArea');
    const formCarrera = document.getElementById('formCarrera');
    const formEspecialidad = document.getElementById('formEspecialidad');

    // Agrupar todas las secciones para facilitar su ocultamiento
    const allSections = [
        listViewAreas, listViewCarreras,
        areaDetailsSection, carreraDetailsSection,
        formArea, formCarrera, formEspecialidad
    ];

    // Botones de "Nuevo"
    const btnNewArea = document.getElementById('btnNewArea');
    const btnNewCarrera = document.getElementById('btnNewCarrera');
    const btnNewEspecialidad = document.getElementById('btnNewEspecialidad'); // Botón dentro del detalle de Carrera

    // Botones de "Volver"
    const btnBackToAreas = document.getElementById('btnBackToAreas');
    const btnBackToCarreras = document.getElementById('btnBackToCarreras');

    // Botones de "Cancelar" en los formularios
    const btnCancelArea = document.getElementById('btnCancelArea');
    const btnCancelCarrera = document.getElementById('btnCancelCarrera');
    const btnCancelEspecialidad = document.getElementById('btnCancelEspecialidad');

    /**
     * Oculta todas las secciones (listas, detalles y formularios).
     */
    function hideAllSections() {
        allSections.forEach(section => {
            section.classList.remove('active');
        });
    }

    /**
     * Activa/desactiva las clases 'active' en los botones de vista principales.
     * @param {HTMLElement} activeButton - El botón que debe estar activo.
     */
    function toggleMainButtonsActive(activeButton) {
        [btnViewAreas, btnViewCarreras].forEach(btn => {
            if (btn === activeButton) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
    }

    /**
     * Carga y muestra la lista de áreas.
     * Utiliza los datos de `window.initialAreas` que vienen del controlador.
     */
    function loadAreas() {
        const areasListDiv = document.getElementById('areasList');
        areasListDiv.innerHTML = ''; // Limpiar lista
        const areas = window.initialAreas || []; // Usa los datos del controlador, si no hay, usa un array vacío

        if (areas.length === 0) {
            areasListDiv.innerHTML = '<p>No hay áreas registradas.</p>';
            return;
        }
        areas.forEach(area => {
            const areaCard = document.createElement('div');
            areaCard.classList.add('data-card');
            areaCard.innerHTML = `
                <h4>${area.nombre}</h4>
                <p>Código: ${area.codigo}</p>
                <p>${area.descripcion ? area.descripcion.substring(0, 100) + (area.descripcion.length > 100 ? '...' : '') : 'Sin descripción'}</p>
                <div class="card-actions">
                    <button class="button_body small-button view-area-details" data-area-id="${area.codigo}">
                        <i class="fas fa-info-circle"></i> Ver Detalles
                    </button>
                </div>
            `;
            areasListDiv.appendChild(areaCard);
        });

        // Añadir event listeners a los nuevos botones de detalles
        document.querySelectorAll('.view-area-details').forEach(button => {
            button.addEventListener('click', (event) => {
                // CORREGIDO: Usar area.codigo (string) como ID, no parseInt
                const areaId = event.currentTarget.dataset.areaId; 
                showAreaDetails(areaId);
            });
        });
    }

    /**
     * Muestra los detalles de un área específica.
     * @param {string} areaId - El CÓDIGO del área a mostrar (es un string).
     */
    function showAreaDetails(areaId) {
        hideAllSections();
        areaDetailsSection.classList.add('active');

        // CORREGIDO: Buscar por 'codigo' en lugar de 'id'
        const area = (window.initialAreas || []).find(a => String(a.codigo) === String(areaId));
        if (!area) {
            document.getElementById('areaDetailName').textContent = 'Área no encontrada';
            document.getElementById('areaDetailDescription').textContent = '';
            document.getElementById('areaCarrerasList').innerHTML = '<p>Detalles del área no disponibles.</p>';
            return;
        }

        document.getElementById('areaDetailName').textContent = `Detalles del Área: ${area.nombre}`;
        document.getElementById('areaDetailDescription').textContent = area.descripcion || 'Sin descripción.';

        const areaCarrerasListDiv = document.getElementById('areaCarrerasList');
        areaCarrerasListDiv.innerHTML = '';

        // CORREGIDO: Usar 'codigo_Area' (con 'A' mayúscula) para la comparación
        const carrerasInArea = (window.initialCarreras || []).filter(carrera => String(carrera.codigo_Area) === String(area.codigo));

        if (carrerasInArea.length === 0) {
            areaCarrerasListDiv.innerHTML = '<p>No hay carreras asociadas a esta área.</p>';
            return;
        }

        carrerasInArea.forEach(carrera => {
            const carreraCard = document.createElement('div');
            carreraCard.classList.add('data-card', 'nested-card');
            carreraCard.innerHTML = `
                <h5>${carrera.nombre}</h5>
                <p>Código: ${carrera.id_carrera}</p>
                <div class="card-actions">
                    <button class="button_body small-button view-carrera-details" data-carrera-id="${carrera.id_carrera}" data-parent-area-id="${area.codigo}">
                        <i class="fas fa-info-circle"></i> Ver Detalles
                    </button>
                </div>
            `;
            areaCarrerasListDiv.appendChild(carreraCard);
        });

        // Añadir event listeners a los nuevos botones de detalles de carrera anidados
        document.querySelectorAll('#areaCarrerasList .view-carrera-details').forEach(button => {
            button.addEventListener('click', (event) => {
                // carreraId es un entero (id_carrera), parentAreaId es un string (codigo)
                const carreraId = parseInt(event.currentTarget.dataset.carreraId);
                const parentAreaId = event.currentTarget.dataset.parentAreaId; 
                showCarreraDetails(carreraId, parentAreaId);
            });
        });
    }

    /**
     * Carga y muestra la lista de carreras.
     * Utiliza los datos de `window.initialCarreras` que vienen del controlador.
     */
    function loadCarreras() {
        const carrerasListDiv = document.getElementById('carrerasList');
        carrerasListDiv.innerHTML = ''; // Limpiar lista
        const carreras = window.initialCarreras || []; // Usa los datos del controlador

        if (carreras.length === 0) {
            carrerasListDiv.innerHTML = '<p>No hay carreras registradas.</p>';
            return;
        }
        carreras.forEach(carrera => {
            // Encuentra el nombre del área asociada
            // CORREGIDO: Asegurar que la comparación sea entre strings
            const area = (window.initialAreas || []).find(a => String(a.codigo) === String(carrera.codigo_Area));
            
            const areaName = area ? area.nombre : 'Área Desconocida';

            const carreraCard = document.createElement('div');
            carreraCard.classList.add('data-card');
            carreraCard.innerHTML = `
                <h4>${carrera.nombre}</h4>
                <p>ID: ${carrera.id_carrera}</p>
                <p>Área: ${areaName}</p>
                <div class="card-actions">
                    <button class="button_body small-button view-carrera-details" data-carrera-id="${carrera.id_carrera}">
                        <i class="fas fa-info-circle"></i> Ver Detalles
                    </button>
                </div>
            `;
            carrerasListDiv.appendChild(carreraCard);
        });

        // Añadir event listeners a los nuevos botones de detalles
        document.querySelectorAll('#carrerasList .view-carrera-details').forEach(button => {
            button.addEventListener('click', (event) => {
                // CORREGIDO: Usar parseInt porque id_carrera es un entero
                const carreraId = parseInt(event.currentTarget.dataset.carreraId);
                showCarreraDetails(carreraId);
            });
        });
    }

    /**
     * Muestra los detalles de una carrera específica.
     * @param {number} carreraId - El ID de la carrera a mostrar (es un entero).
     * @param {string|null} [parentAreaId=null] - El CÓDIGO del área padre si se navegó desde el detalle de un área (es un string).
     */
    function showCarreraDetails(carreraId, parentAreaId = null) {
        hideAllSections();
        carreraDetailsSection.classList.add('active');

        // Almacenar el ID de la carrera actual en un dataset para que el botón "Nueva Especialidad" pueda acceder a él.
        // CORREGIDO: Almacena el id_carrera (entero)
        carreraDetailsSection.dataset.currentCarreraId = carreraId;

        // Ajustar el botón de "Volver"
        if (parentAreaId) {
            btnBackToCarreras.innerHTML = '<i class="fas fa-arrow-left"></i> Volver al Área';
            // CORREGIDO: Almacenar el código del área (string)
            btnBackToCarreras.dataset.backToAreaId = parentAreaId; 
        } else {
            btnBackToCarreras.innerHTML = '<i class="fas fa-arrow-left"></i> Volver a Carreras';
            delete btnBackToCarreras.dataset.backToAreaId;
        }
        console.log('Current Carrera ID:', carreraId);
        // CORREGIDO: Buscar por 'id_carrera' (entero)
        const carrera = (window.initialCarreras || []).find(c => c.id_carrera === carreraId);
        if (!carrera) {
            document.getElementById('carreraDetailName').textContent = 'Carrera no encontrada';
            document.getElementById('carreraDetailId').textContent = '';
            document.getElementById('carreraEspecialidadesList').innerHTML = '<p>Detalles de la carrera no disponibles.</p>';
            return;
        }

        document.getElementById('carreraDetailName').textContent = `Detalles de la Carrera: ${carrera.nombre}`;
        document.getElementById('carreraDetailId').textContent = carrera.id_carrera; // Usa id_carrera como el código visible

        const carreraEspecialidadesListDiv = document.getElementById('carreraEspecialidadesList');
        carreraEspecialidadesListDiv.innerHTML = '';

        // Filtrar especialidades que pertenecen a esta carrera
        // La comparación e.id_carrera === carrera.id_carrera es correcta (ambos son enteros)
        const especialidadesInCarrera = (window.initialEspecialidades || []).filter(e => e.id_carrera === carrera.id_carrera); 

        if (especialidadesInCarrera.length === 0) {
            carreraEspecialidadesListDiv.innerHTML = '<p>No hay especialidades asociadas a esta carrera.</p>';
        } else {
            especialidadesInCarrera.forEach(especialidad => {
                const especialidadCard = document.createElement('div');
                especialidadCard.classList.add('data-card', 'nested-card');
                especialidadCard.innerHTML = `
                    <h5>${especialidad.nombre}</h5>
                    <p>Código: ${especialidad.codigo_especialidad}</p>
                `;
                carreraEspecialidadesListDiv.appendChild(especialidadCard);
            });
        }
    }

    /**
     * Rellena el elemento <select> del formulario de Carrera con las áreas disponibles.
     * Utiliza los datos de `window.initialAreas`.
     */
    function populateAreaSelect() {
        const selectElement = document.getElementById('carrera_area_id_new');
        selectElement.innerHTML = '<option value="">Seleccione un Área</option>'; // Limpiar y añadir opción por defecto

        const areas = window.initialAreas || [];
        areas.forEach(area => {
            const option = document.createElement('option');
            option.value = area.codigo; // 'codigo' es el valor correcto para enlazar con 'codigo_Area' en Carrera
            option.textContent = area.nombre;
            selectElement.appendChild(option);
        });
    }

    /**
     * Rellena el elemento <select> del formulario de Especialidad con las carreras disponibles.
     * Puede preseleccionar una carrera si se proporciona `selectedCarreraCode`.
     * Utiliza los datos de `window.initialCarreras`.
     * @param {number|null} selectedCarreraId - El ID de la carrera a preseleccionar (id_carrera, es un entero).
     */
    function populateCarreraSelect(selectedCarreraId = null) {
        const selectElement = document.getElementById('especialidad_carrera_id_new');
        selectElement.innerHTML = '<option value="">Seleccione una Carrera</option>'; // Limpiar y añadir opción por defecto

        const carreras = window.initialCarreras || [];
        carreras.forEach(carrera => {
            const option = document.createElement('option');
            option.value = carrera.id_carrera; // 'id_carrera' es el valor correcto para enlazar con 'id_carrera' en Especialidad
            option.textContent = carrera.nombre;
            // CORREGIDO: Comparar con el id_carrera (entero)
            if (selectedCarreraId !== null && carrera.id_carrera === selectedCarreraId) { 
                option.selected = true;
            }
            selectElement.appendChild(option);
        });
    }


    // --- Event Listeners principales de navegación ---

    btnViewAreas.addEventListener('click', () => {
        hideAllSections();
        listViewAreas.classList.add('active');
        toggleMainButtonsActive(btnViewAreas);
        loadAreas(); // Recargar la lista de áreas
    });

    btnViewCarreras.addEventListener('click', () => {
        hideAllSections();
        listViewCarreras.classList.add('active');
        toggleMainButtonsActive(btnViewCarreras);
        loadCarreras(); // Recargar la lista de carreras
    });

    // --- Event Listeners para botones "Nuevo" ---
    btnNewArea.addEventListener('click', () => {
        hideAllSections();
        formArea.classList.add('active');
        formArea.querySelector('form').reset(); // Limpiar campos del formulario
    });

    btnNewCarrera.addEventListener('click', () => {
        hideAllSections();
        formCarrera.classList.add('active');
        formCarrera.querySelector('form').reset(); // Limpiar campos del formulario
        populateAreaSelect(); // Poblar el dropdown de áreas
    });

    btnNewEspecialidad.addEventListener('click', () => {
        hideAllSections();
        formEspecialidad.classList.add('active');
        formEspecialidad.querySelector('form').reset(); // Limpiar campos del formulario

        // Obtener el ID de la carrera actual desde el dataset de la sección de detalles de carrera
        // CORREGIDO: currentCarreraId es el id_carrera (entero)
        const currentCarreraId = parseInt(carreraDetailsSection.dataset.currentCarreraId); 
        
        // No necesitamos buscar la carrera de nuevo, ya tenemos su id_carrera
        populateCarreraSelect(currentCarreraId);
    });

    // --- Event Listeners para botones "Volver" ---
    btnBackToAreas.addEventListener('click', () => {
        hideAllSections();
        listViewAreas.classList.add('active');
        toggleMainButtonsActive(btnViewAreas);
        loadAreas(); // Asegurarse de que la lista esté actualizada
    });

    btnBackToCarreras.addEventListener('click', (event) => {
        // CORREGIDO: backToAreaId es un string (codigo del área)
        const backToAreaId = event.currentTarget.dataset.backToAreaId;
        if (backToAreaId) {
            // Si venimos del detalle de un área, volvemos al detalle de esa área
            // CORREGIDO: No usar parseInt para areaId (es un string)
            showAreaDetails(backToAreaId);
        } else {
            // Si venimos directamente de la lista de carreras, volvemos a esa lista
            hideAllSections();
            listViewCarreras.classList.add('active');
            toggleMainButtonsActive(btnViewCarreras);
            loadCarreras();
        }
    });

    // --- Event Listeners para botones "Cancelar" en formularios ---
    btnCancelArea.addEventListener('click', () => {
        hideAllSections();
        listViewAreas.classList.add('active');
        toggleMainButtonsActive(btnViewAreas);
        loadAreas();
    });

    btnCancelCarrera.addEventListener('click', () => {
        hideAllSections();
        listViewCarreras.classList.add('active');
        toggleMainButtonsActive(btnViewCarreras);
        loadCarreras();
    });

    btnCancelEspecialidad.addEventListener('click', () => {
        // Al cancelar en el formulario de especialidad, lo más lógico es volver a los detalles de la carrera
        // CORREGIDO: currentCarreraId es el id_carrera (entero)
        const currentCarreraId = parseInt(carreraDetailsSection.dataset.currentCarreraId); 
        if (currentCarreraId) {
            // Verifica si el retorno es a la lista de carreras o a los detalles de un área
            // CORREGIDO: parentAreaId es un string (codigo del área)
            const parentAreaId = btnBackToCarreras.dataset.backToAreaId; 
            showCarreraDetails(currentCarreraId, parentAreaId || null);
        } else {
            // Si por alguna razón no hay carrera actual (ej. se accedió al formulario de especialidad directamente),
            // se vuelve a la lista general de carreras.
            hideAllSections();
            listViewCarreras.classList.add('active');
            toggleMainButtonsActive(btnViewCarreras);
            loadCarreras();
        }
    });


    loadAreas(); // Initial load of areas when the page loads
    toggleMainButtonsActive(btnViewAreas); // Ensure the "View Areas" button is active initially
});
