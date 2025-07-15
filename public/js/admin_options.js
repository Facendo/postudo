document.addEventListener('DOMContentLoaded', () => {
    // Referencias a los botones principales de vista
    const btnViewAreas = document.getElementById('btnViewAreas');
    const btnViewCarreras = document.getElementById('btnViewCarreras');

    // Referencias a las secciones de lista y detalle
    const listViewAreas = document.getElementById('listViewAreas');
    const listViewCarreras = document.getElementById('listViewCarreras');
    const areaDetailsSection = document.getElementById('areaDetailsSection');
    const carreraDetailsSection = document.getElementById('carreraDetailsSection');


    // Formular container para formularios DELETE
    const deleteFormsContainer = document.getElementById('deleteFormsContainer');
    if (!deleteFormsContainer) {
        console.error('Error: #deleteFormsContainer not found in DOM.');
        return;
    }

    // Formularios de registro y botones de nuevo/cancelar/volver
    const formArea = document.getElementById('formArea');
    const formCarrera = document.getElementById('formCarrera');
    const formEspecialidad = document.getElementById('formEspecialidad');
    const btnNewArea = document.getElementById('btnNewArea');
    const btnNewCarrera = document.getElementById('btnNewCarrera');
    const btnNewEspecialidad = document.getElementById('btnNewEspecialidad');
    const btnBackToAreas = document.getElementById('btnBackToAreas');
    const btnBackToCarreras = document.getElementById('btnBackToCarreras');

    const btnCancelArea = document.getElementById('btnCancelArea');
    const btnCancelCarrera = document.getElementById('btnCancelCarrera');
    const btnCancelEspecialidad = document.getElementById('btnCancelEspecialidad');


    // Agrupar todas las secciones para ocultar/mostrar
    const allSections = [
        listViewAreas, listViewCarreras,
        areaDetailsSection, carreraDetailsSection,
        formArea, formCarrera, formEspecialidad
    ];

    function hideAllSections() {
        allSections.forEach(sec => sec && sec.classList.remove('active'));
    }

    function toggleMainButtonsActive(activeBtn) {
        [btnViewAreas, btnViewCarreras].forEach(btn => {
            btn.classList.toggle('active', btn === activeBtn);
        });
    }

    // --------------------------------------------------------------------------------
    // CARGA DE ÁREAS
    // --------------------------------------------------------------------------------
    function loadAreas() {
        const areasListDiv = document.getElementById('areasList');
        areasListDiv.innerHTML = '';
        deleteFormsContainer.innerHTML = '';
        const areas = window.initialAreas || [];

        if (!areas.length) {
            areasListDiv.innerHTML = '<p>No hay áreas registradas.</p>';
            return;
        }

        areas.forEach(area => {
            const card = document.createElement('div');
            card.classList.add('data-card');
            card.innerHTML = `
                <h4>${area.nombre}</h4>
                <p>Código: ${area.codigo}</p>
                <p>${area.descripcion ? area.descripcion.substring(0,100) + (area.descripcion.length>100?'...':'') : 'Sin descripción'}</p>
                <div class="card-actions">
                    <button class="button_body small-button view-area-details" data-area-id="${area.codigo}"><i class="fas fa-info-circle"></i> Ver Detalles</button>
                    <button class="button_body small-button edit-area-btn warning" data-area-id="${area.codigo}"><i class="fas fa-edit"></i> Editar</button>
                    <button class="button_body small-button delete-area-btn danger" data-area-id="${area.codigo}" data-area-name="${area.nombre}"><i class="fas fa-trash-alt"></i> Eliminar</button>
                </div>
            `;
            areasListDiv.appendChild(card);

            // Formulario DELETE oculto
            const form = document.createElement('form');
            form.id = `delete-area-form-${area.codigo}`;
            form.action = `/administrador/areas/${area.codigo}`;
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
            `;
            deleteFormsContainer.appendChild(form);
        });

        areasListDiv.removeEventListener('click', handleAreaActions);
        areasListDiv.addEventListener('click', handleAreaActions);
    }

    function handleAreaActions(e) {
        const btn = e.target.closest('button'); 
        if (!btn) return;
        const id = btn.dataset.areaId;

        if (btn.classList.contains('view-area-details')) {
            showAreaDetails(id);
        } else if (btn.classList.contains('edit-area-btn')) {
            editArea(id);
        } else if (btn.classList.contains('delete-area-btn')) {
            const name = btn.dataset.areaName;
            if (confirm(`¿Eliminar área "${name}"?`)) {
                document.getElementById(`delete-area-form-${id}`).submit();
            }
        }
    }

    // --------------------------------------------------------------------------------
    // DETALLES DE ÁREA
    // --------------------------------------------------------------------------------
    function showAreaDetails(areaId) {
        hideAllSections(); areaDetailsSection.classList.add('active');
        deleteFormsContainer.innerHTML = '';
        const area = (window.initialAreas||[]).find(a=>String(a.codigo)===String(areaId));
        document.getElementById('areaDetailName').textContent = `Detalles del Área: ${area?area.nombre:'No encontrada'}`;
        document.getElementById('areaDetailDescription').textContent = area?.descripcion || 'Sin descripción.';
        const list = document.getElementById('areaCarrerasList'); list.innerHTML='';

        const carreras = (window.initialCarreras||[]).filter(c=>String(c.codigo_Area)===String(areaId));
        if (!carreras.length) {
            list.innerHTML = '<p>No hay carreras asociadas.</p>';
        } else {
            carreras.forEach(c=>{
                const card = document.createElement('div'); card.classList.add('data-card','nested-card');
                card.innerHTML = `
                    <h5>${c.nombre}</h5>
                    <p>ID: ${c.id_carrera}</p>
                    <div class="card-actions">
                        <button class="button_body small-button view-carrera-details" data-carrera-id="${c.id_carrera}" data-parent-area-id="${areaId}">
                            <i class="fas fa-info-circle"></i> Ver Detalles
                        </button>
                        <button class="button_body small-button edit-carrera-btn warning" data-carrera-id="${c.id_carrera}">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <button class="button_body small-button delete-carrera-btn danger" data-carrera-id="${c.id_carrera}" data-carrera-name="${c.nombre}">
                            <i class="fas fa-trash-alt"></i> Eliminar
                        </button>
                    </div>
                `;
                list.appendChild(card);

                // DELETE form
                const f = document.createElement('form');
                f.id = `delete-carrera-form-${c.id_carrera}`;
                f.action = `/administrador/carrera/${c.id_carrera}`;
                f.method = 'POST';
                f.innerHTML = `
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                `;
                deleteFormsContainer.appendChild(f);
            });
        }
        list.removeEventListener('click', handleCarreraActions);
        list.addEventListener('click', handleCarreraActions);
    }

    function handleCarreraActions(e) {
        const btn = e.target.closest('button');
        if (!btn) return;
        
        const id = btn.dataset.carreraId;
        
        if (btn.classList.contains('view-carrera-details')) {
            showCarreraDetails(parseInt(id), btn.dataset.parentAreaId);
        }
        else if (btn.classList.contains('edit-carrera-btn')) {
            editCarrera(parseInt(id));
        }
        else if (btn.classList.contains('delete-carrera-btn')) {
            const name = btn.dataset.carreraName;
            if (confirm(`¿Eliminar carrera "${name}"?`)) {
                document.getElementById(`delete-carrera-form-${id}`).submit();
            }
        }
    }

    // --------------------------------------------------------------------------------
    // LISTA GENERAL DE CARRERAS
    // --------------------------------------------------------------------------------
    function loadCarreras() {
        const div = document.getElementById('carrerasList'); div.innerHTML='';
        deleteFormsContainer.innerHTML='';
        const carreras = window.initialCarreras || [];
        if (!carreras.length) { div.innerHTML='<p>No hay carreras registradas.</p>'; return; }
        carreras.forEach(c=>{
            const area = (window.initialAreas||[]).find(a=>String(a.codigo)===String(c.codigo_Area));
            const card = document.createElement('div'); card.classList.add('data-card');
            card.innerHTML = `
                <h4>${c.nombre}</h4>
                <p>ID: ${c.id_carrera}</p>
                <p>Área: ${area?area.nombre:'-'}</p>
                <div class="card-actions">
                    <button class="button_body small-button view-carrera-details" data-carrera-id="${c.id_carrera}">
                        <i class="fas fa-info-circle"></i> Ver Detalles
                    </button>
                    <button class="button_body small-button edit-carrera-btn warning" data-carrera-id="${c.id_carrera}">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button class="button_body small-button delete-carrera-btn danger" data-carrera-id="${c.id_carrera}" data-carrera-name="${c.nombre}">
                        <i class="fas fa-trash-alt"></i> Eliminar
                    </button>
                </div>
            `;
            div.appendChild(card);
            const f = document.createElement('form'); f.id = `delete-carrera-form-${c.id_carrera}`;
            f.action = `/administrador/carrera/${c.id_carrera}`; f.method='POST';
            f.innerHTML=`<input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">`;
            deleteFormsContainer.appendChild(f);
        });
        div.removeEventListener('click', handleCarreraActions);
        div.addEventListener('click', handleCarreraActions);
    }

    // --------------------------------------------------------------------------------
    // DETALLES DE CARRERA Y ESPECIALIDADES
    // --------------------------------------------------------------------------------
    function showCarreraDetails(carreraId, parentAreaId=null) {
        hideAllSections(); carreraDetailsSection.classList.add('active');
        carreraDetailsSection.dataset.currentCarreraId = carreraId;
        if (parentAreaId) {
            btnBackToCarreras.textContent = '← Volver al Área';
            btnBackToCarreras.dataset.backToAreaId = parentAreaId;
        } else {
            btnBackToCarreras.textContent = '← Volver a Carreras';
            delete btnBackToCarreras.dataset.backToAreaId;
        }
        deleteFormsContainer.innerHTML='';
        const carrera = (window.initialCarreras||[]).find(x=>x.id_carrera===carreraId);
        document.getElementById('carreraDetailName').textContent = `Detalles de la Carrera: ${carrera?carrera.nombre:'No encontrada'}`;
        document.getElementById('carreraDetailId').textContent = carrera?carrera.id_carrera:'';
        const list = document.getElementById('carreraEspecialidadesList'); list.innerHTML='';
        const esp = (window.initialEspecialidades||[]).filter(e=>e.id_carrera===carreraId);
        if (!esp.length) {
            list.innerHTML='<p>No hay especialidades asociadas.</p>';
        } else {
            esp.forEach(e=>{
                const card = document.createElement('div'); card.classList.add('data-card','nested-card');
                card.innerHTML = `
                    <h5>${e.nombre}</h5>
                    <p>Código: ${e.codigo_especialidad}</p>
                    <div class="card-actions">
                        <button class="button_body small-button edit-especialidad-btn warning" data-especialidad-id="${e.codigo_especialidad}"><i class="fas fa-edit"></i> Editar</button>
                        <button class="button_body small-button delete-especialidad-btn danger" data-especialidad-id="${e.codigo_especialidad}" data-especialidad-name="${e.nombre}"><i class="fas fa-trash-alt"></i> Eliminar</button>
                    </div>
                `;
                list.appendChild(card);
                const f = document.createElement('form'); 
                f.id = `delete-especialidad-form-${e.codigo_especialidad}`;
                f.action = `/administrador/especialidad/${e.codigo_especialidad}`;
                f.method = 'POST';
                f.innerHTML = `
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                `;
                deleteFormsContainer.appendChild(f);
            });
        }
        list.removeEventListener('click', handleEspecialidadActions);
        list.addEventListener('click', handleEspecialidadActions);
    }

    function handleEspecialidadActions(e) {
        const btn = e.target.closest('button'); 
        if (!btn) return;
        const id = btn.dataset.especialidadId;
        
        if (btn.classList.contains('edit-especialidad-btn')) {
            editEspecialidad(parseInt(id));
        } else if (btn.classList.contains('delete-especialidad-btn')) {
            if (confirm(`¿Eliminar especialidad ${btn.dataset.especialidadName}?`)) {
                document.getElementById(`delete-especialidad-form-${id}`).submit();
            }
        }
    }

    // --------------------------------------------------------------------------------
    // EDITAR ÁREA, CARRERA Y ESPECIALIDAD
    // --------------------------------------------------------------------------------

    function editArea(areaId) {
        const area = (window.initialAreas||[]).find(a=>String(a.codigo)===String(areaId));
        if (!area) {
            alert('Área no encontrada');
            return;
        }
        hideAllSections(); formArea.classList.add('active');
        formArea.querySelector('form').reset();
        formArea.querySelector('input[name="codigo"]').value = area.codigo;
        formArea.querySelector('input[name="nombre"]').value = area.nombre;
        formArea.querySelector('textarea[name="descripcion"]').value = area.descripcion || '';
        formArea.querySelector('input[name="_method"]').value = 'PUT';
        formArea.querySelector('input[name="_token"]').value = document.querySelector('meta[name="csrf-token"]').content;
    }
    function editCarrera(carreraId) {
        const carrera = (window.initialCarreras||[]).find(c=>c.id_carrera===carreraId);
        if (!carrera) {
            alert('Carrera no encontrada');
            return;
        }
        hideAllSections(); formCarrera.classList.add('active');
        formCarrera.querySelector('form').reset();
        formCarrera.querySelector('input[name="id_carrera"]').value = carrera.id_carrera;
        formCarrera.querySelector('input[name="nombre"]').value = carrera.nombre;
        formCarrera.querySelector('input[name="codigo_Area"]').value = carrera.codigo_Area;
        formCarrera.querySelector('textarea[name="descripcion"]').value = carrera.descripcion || '';
        formCarrera.querySelector('input[name="_method"]').value = 'PUT';
        formCarrera.querySelector('input[name="_token"]').value = document.querySelector('meta[name="csrf-token"]').content;
        populateAreaSelect();
        const areaSelect = formCarrera.querySelector('select[name="codigo_Area"]');
        areaSelect.value = carrera.codigo_Area;
        areaSelect.dispatchEvent(new Event('change')); // For any JS that listens to changes
    }
    function editEspecialidad(especialidadId) {
        const especialidad = (window.initialEspecialidades||[]).find(e=>e.id===especialidadId);
        if (!especialidad) {
            alert('Especialidad no encontrada');
            return;
        }
        hideAllSections(); formEspecialidad.classList.add('active');
        formEspecialidad.querySelector('form').reset();
        formEspecialidad.querySelector('input[name="id"]').value = especialidad.id;
        formEspecialidad.querySelector('input[name="nombre"]').value = especialidad.nombre;
        formEspecialidad.querySelector('input[name="codigo_especialidad"]').value = especialidad.codigo_especialidad;
        formEspecialidad.querySelector('input[name="id_carrera"]').value = especialidad.id_carrera;
        formEspecialidad.querySelector('textarea[name="descripcion"]').value = especialidad.descripcion || '';
        formEspecialidad.querySelector('input[name="_method"]').value = 'PUT';
        formEspecialidad.querySelector('input[name="_token"]').value = document.querySelector('meta[name="csrf-token"]').content;
        populateCarreraSelect(especialidad.id_carrera);
        const carreraSelect = formEspecialidad.querySelector('select[name="id_carrera"]');
        carreraSelect.value = especialidad.id_carrera;
        carreraSelect.dispatchEvent(new Event('change')); // For any JS that listens to changes
    }

    // --------------------------------------------------------------------------------
    // POBLAR SELECTS EN FORMULARIOS
    // --------------------------------------------------------------------------------
    function populateAreaSelect() {
        const sel = document.getElementById('carrera_area_id_new'); sel.innerHTML='<option value="">Seleccione un Área</option>';
        (window.initialAreas||[]).forEach(a=> sel.append(new Option(a.nombre, a.codigo)));
    }
    function populateCarreraSelect(selId=null) {
        const sel = document.getElementById('especialidad_carrera_id_new'); sel.innerHTML='<option value="">Seleccione una Carrera</option>';
        window.initialCarreras.forEach(c=>{
            const opt = new Option(c.nombre, c.id_carrera);
            if (selId!==null && c.id_carrera===selId) opt.selected=true;
            sel.append(opt);
        });
    }

    // --------------------------------------------------------------------------------
    // EVENTOS PRINCIPALES
    // --------------------------------------------------------------------------------
    btnViewAreas.addEventListener('click', ()=>{ hideAllSections(); listViewAreas.classList.add('active'); toggleMainButtonsActive(btnViewAreas); loadAreas(); });
    btnViewCarreras.addEventListener('click', ()=>{ hideAllSections(); listViewCarreras.classList.add('active'); toggleMainButtonsActive(btnViewCarreras); loadCarreras(); });
    btnNewArea.addEventListener('click', ()=>{ hideAllSections(); formArea.classList.add('active'); formArea.querySelector('form').reset(); });
    btnNewCarrera.addEventListener('click', ()=>{ hideAllSections(); formCarrera.classList.add('active'); formCarrera.querySelector('form').reset(); populateAreaSelect(); });
    btnNewEspecialidad.addEventListener('click', ()=>{ hideAllSections(); formEspecialidad.classList.add('active'); formEspecialidad.querySelector('form').reset(); populateCarreraSelect(parseInt(carreraDetailsSection.dataset.currentCarreraId)); });
    btnBackToAreas.addEventListener('click', ()=>{ hideAllSections(); listViewAreas.classList.add('active'); toggleMainButtonsActive(btnViewAreas); loadAreas(); });
    btnBackToCarreras.addEventListener('click', e=>{ const aid = e.currentTarget.dataset.backToAreaId; if(aid) showAreaDetails(aid); else { hideAllSections(); listViewCarreras.classList.add('active'); toggleMainButtonsActive(btnViewCarreras); loadCarreras(); } });
    btnCancelArea.addEventListener('click', ()=>btnViewAreas.click());
    btnCancelCarrera.addEventListener('click', ()=>btnViewCarreras.click());
    btnCancelEspecialidad.addEventListener('click', ()=>{ const cid= parseInt(carreraDetailsSection.dataset.currentCarreraId); const aid= btnBackToCarreras.dataset.backToAreaId; if(cid) showCarreraDetails(cid, aid); else btnViewCarreras.click(); });

    // Carga inicial
    loadAreas(); toggleMainButtonsActive(btnViewAreas);

});
