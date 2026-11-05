document.addEventListener('DOMContentLoaded', () => {
    // --------------------------------------------------------------------------------
    // Gestión de Áreas (Área -> Postgrado -> Mención)
    //  1) Desplegar los postgrados de un área al pulsar su fila
    //  2) Mostrar/ocultar el formulario de crear / editar
    //  3) Eliminar con confirmación previa del impacto en cascada
    //
    // El listado lo pinta Blade en el servidor (window.initialAreas ya no hace falta).
    // --------------------------------------------------------------------------------
    const listViewAreas = document.getElementById('listViewAreas');
    const formArea = document.getElementById('formArea');
    const btnNewArea = document.getElementById('btnNewArea');
    const btnCancelArea = document.getElementById('btnCancelArea');
    const areaForm = document.getElementById('areaForm');

    if (!listViewAreas || !formArea || !areaForm || !btnNewArea || !btnCancelArea) {
        console.error('Error: contenedores de la página de áreas no encontrados en el DOM.');
        return;
    }

    // --------------------------------------------------------------------------------
    // 1) DESPLEGAR POSTGRADOS DE UN ÁREA (fila hija oculta)
    // --------------------------------------------------------------------------------
    document.querySelectorAll('.fila-area').forEach(fila => {
        const alternar = () => {
            const detalle = fila.nextElementSibling;
            if (!detalle || !detalle.classList.contains('fila-postgrados')) return;

            // Se usa la clase .hidden (display:none !important) en vez del
            // atributo hidden, porque en móvil el CSS global de tablas
            // fuerza "tr { display: block }" y lo dejaría visible.
            const estabaOculta = detalle.classList.contains('hidden');
            detalle.classList.toggle('hidden', !estabaOculta);

            fila.classList.toggle('fila-activa', estabaOculta);

            const flecha = fila.querySelector('.flecha-fila');
            if (flecha) {
                flecha.classList.toggle('fa-chevron-right', !estabaOculta);
                flecha.classList.toggle('fa-chevron-down', estabaOculta);
            }
        };

        // Clic en la fila (los botones de Editar/Eliminar no deben desplegarla)
        fila.addEventListener('click', evento => {
            if (evento.target.closest('.table-actions')) return;
            alternar();
        });

        // Accesibilidad: Enter o Espacio sobre la fila
        fila.addEventListener('keydown', evento => {
            if (evento.key !== 'Enter' && evento.key !== ' ') return;
            if (evento.target.closest('.table-actions')) return;
            evento.preventDefault();
            alternar();
        });
    });

    // --------------------------------------------------------------------------------
    // 2) FORMULARIO DE CREAR / EDITAR
    // --------------------------------------------------------------------------------
    function mostrarFormulario() {
        listViewAreas.classList.add('hidden');
        formArea.classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function mostrarListado() {
        formArea.classList.add('hidden');
        listViewAreas.classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Quita la marca PUT que pudiera quedar de una edición anterior
    function prepararFormulario() {
        const metodo = areaForm.querySelector('input[name="_method"]');
        if (metodo) metodo.remove();
        document.getElementById('codigo').readOnly = false;
    }

    btnNewArea.addEventListener('click', () => {
        prepararFormulario();
        document.getElementById('formAreaTitle').textContent = 'Registrar Nueva Área';
        areaForm.action = window.routes.storeArea;
        areaForm.reset();
        mostrarFormulario();
    });

    document.querySelectorAll('.editar-area').forEach(boton => {
        boton.addEventListener('click', () => {
            const fila = boton.closest('.fila-area');
            if (!fila) return;

            const codigo = fila.dataset.codigo;

            prepararFormulario();

            // Mismo formulario, ahora apuntando a la ruta PUT del área
            areaForm.action = `${window.routes.baseAreas}/${encodeURIComponent(codigo)}`;
            const metodo = document.createElement('input');
            metodo.type = 'hidden';
            metodo.name = '_method';
            metodo.value = 'PUT';
            areaForm.appendChild(metodo);

            document.getElementById('formAreaTitle').textContent = 'Editar Área';
            areaForm.reset();

            document.getElementById('codigo').value = fila.dataset.codigo || '';
            document.getElementById('codigo').readOnly = true; // el código es la clave primaria
            document.getElementById('nombre').value = fila.dataset.nombre || '';
            document.getElementById('area_description_new').value = fila.dataset.descripcion || '';

            mostrarFormulario();
        });
    });

    btnCancelArea.addEventListener('click', mostrarListado);

    // Tras un error de validación el panel del formulario ya llega abierto y con
    // los valores antiguos. Si el código antiguo existe en la tabla (y el error
    // no es de código), el envío fallido fue una EDICIÓN, así que se restaura
    // el modo PUT sobre esa área; si no, se queda en modo "registrar".
    const errores = window.erroresValidacion || [];
    if (errores.length && !formArea.classList.contains('hidden')) {
        listViewAreas.classList.add('hidden'); // solo un cuadro visible: el formulario
        const codigo = String(window.codigoAntiguo || '').trim();
        const esEdicion = !errores.includes('codigo') && codigo !== '' &&
            Array.from(document.querySelectorAll('.fila-area'))
                .some(f => String(f.dataset.codigo) === codigo);

        prepararFormulario();

        if (esEdicion) {
            areaForm.action = `${window.routes.baseAreas}/${encodeURIComponent(codigo)}`;
            const metodo = document.createElement('input');
            metodo.type = 'hidden';
            metodo.name = '_method';
            metodo.value = 'PUT';
            areaForm.appendChild(metodo);
            document.getElementById('formAreaTitle').textContent = 'Editar Área';
            document.getElementById('codigo').readOnly = true;
        } else {
            document.getElementById('formAreaTitle').textContent = 'Registrar Nueva Área';
            areaForm.action = window.routes.storeArea;
        }
    }

    // --------------------------------------------------------------------------------
    // 3) ELIMINAR ÁREA CON CONFIRMACIÓN PREVIA (impacto en cascada)
    // --------------------------------------------------------------------------------
    async function impactoArea(codigo) {
        try {
            const respuesta = await fetch(
                `${window.routes.baseAreas}/${encodeURIComponent(codigo)}/impacto`,
                { headers: { 'Accept': 'application/json' } }
            );
            if (!respuesta.ok) return null;
            return await respuesta.json();
        } catch (e) {
            return null;
        }
    }

    function textoImpacto(impacto) {
        const partes = [];
        if (impacto.postgrados) partes.push(`${impacto.postgrados} postgrado(s)`);
        if (impacto.menciones) partes.push(`${impacto.menciones} mención(es)`);
        if (impacto.cohortes) partes.push(`${impacto.cohortes} cohorte(s)`);

        if (!partes.length) {
            return '\n\nEl área no tiene postgrados asociados.';
        }

        return '\n\nSe eliminará en cascada:\n- ' + partes.join('\n- ') +
            '\n\nLos estudiantes NO se borran, pero quedarán sin mención ni postgrado asignado.';
    }

    document.querySelectorAll('.eliminar-area').forEach(boton => {
        boton.addEventListener('click', async () => {
            const codigo = boton.dataset.codigo;
            const nombre = boton.dataset.nombre;

            let mensaje = `¿Eliminar el área "${nombre}"?`;

            const impacto = await impactoArea(codigo);
            mensaje += impacto
                ? textoImpacto(impacto)
                : ' Se eliminarán sus postgrados, menciones y cohortes en cascada.';

            if (confirm(mensaje)) {
                const formulario = document.getElementById('delete-area-form-' + codigo);
                if (formulario) formulario.submit();
            }
        });
    });
});
