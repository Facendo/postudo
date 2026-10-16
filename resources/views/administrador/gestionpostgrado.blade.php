
<x-layout title='PostUDO || Postgrados'>
    <div class="page-content" style="width: 90%; max-width: 1200px; margin: 40px auto; display: flex; flex-direction: column; align-items: center;">
        {{-- Título de la sección --}}
        <div class="content_texto_bienvenida">
            <label>Listado de Postgrados</label>
        </div>

        {{-- Botón para crear un nuevo postgrado --}}
        <div class="action-buttons-container">
            {{-- Se asume que la ruta para crear un postgrado es 'administrador.gestion_postgrado.create' --}}
            <a href="{{ route('administrador.gestion_postgrado.create') }}" class="button_body">
                <i class="fa-solid fa-plus icon-left"></i> Nuevo Postgrado
            </a>
        </div>
        {{-- Tabla para mostrar los postgrados --}}
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Duración</th>
                    <th>Área</th>
                    <th>Nro. Cohortes</th>
                    <th>Detalles</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                {{--
                    Bucle @forelse para iterar sobre la colección de $postgrados.
                    Muestra una fila por cada postgrado.
                --}}
                @forelse ($postgrados as $postgrado)
                    <tr>
                        <td>{{ $postgrado->id_postgrado }}</td>
                        <td>{{ $postgrado->nombre }}</td>
                        <td>{{ $postgrado->descripcion }}</td>
                        <td>{{ $postgrado->duracion }}</td>
                        <td>{{ $postgrado->area->nombre ?? 'Sin área' }}</td>
                        <td>{{ $postgrado->nro_cohortes }}</td>
                        <td>
                            {{-- Enlace a la vista de detalles del postgrado --}}
                            <a href="{{ route('administrador.gestion_postgrado.showdetalles', $postgrado->id_postgrado) }}" class="btn-table-action" title="Ver Detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                        
                        {{-- Columna de acciones con botones para Editar y Eliminar --}}
                        <td class="table-actions">
                            {{-- Botón de Editar --}}
                            {{-- Se asume que la ruta para editar es 'administrador.gestion_postgrado.edit' --}}
                            <a href="{{ route('administrador.gestion_postgrado.edit', $postgrado->id_postgrado) }}" class="btn-table-action" title="Editar">
                                <i class="fas fa-pencil-alt"></i>
                            </a>

                            {{-- Botón de Eliminar (usando un formulario para solicitudes DELETE) --}}
                            {{-- Se asume que la ruta para eliminar es 'administrador.gestion_postgrado.destroy' --}}
                            <form action="{{ route('administrador.gestion_postgrado.destroy', $postgrado->id_postgrado) }}" method="POST" class="inline-form"
                                data-eliminar-postgrado="{{ $postgrado->id_postgrado }}" data-nombre="{{ $postgrado->nombre }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-table-action" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    {{--
                        Este bloque se muestra si la colección $postgrados está vacía.
                        El colspan se ajusta a 7 para coincidir con el número de columnas.
                    --}}
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 20px;">No hay postgrados registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        // Confirmación previa al borrado: muestra todo lo que se elimina en cascada.
        document.querySelectorAll('form[data-eliminar-postgrado]').forEach(form => {
            form.addEventListener('submit', async (evento) => {
                if (form.dataset.confirmado === '1') return;

                evento.preventDefault();

                let mensaje = `¿Eliminar el postgrado "${form.dataset.nombre}"?`;

                try {
                    const respuesta = await fetch(
                        `{{ url('/administrador/gestion_postgrado') }}/${encodeURIComponent(form.dataset.eliminarPostgrado)}/impacto`,
                        { headers: { 'Accept': 'application/json' } }
                    );

                    if (respuesta.ok) {
                        const impacto = await respuesta.json();
                        const partes = [];
                        if (impacto.menciones) partes.push(`${impacto.menciones} mención(es)`);
                        if (impacto.cohortes) partes.push(`${impacto.cohortes} cohorte(s)`);
                        if (impacto.materias) partes.push(`${impacto.materias} materia(s)`);
                        if (impacto.secciones) partes.push(`${impacto.secciones} sección(es)`);
                        if (impacto.listados) partes.push(`${impacto.listados} inscripción(es) en listados`);

                        if (partes.length) {
                            mensaje += '\n\nSe eliminará en cascada:\n- ' + partes.join('\n- ');
                        }

                        if (impacto.estudiantes_desasignados) {
                            mensaje += `\n\n${impacto.estudiantes_desasignados} estudiante(s) quedarán SIN mención asignada (no se borran).`;
                        }
                    }
                } catch (error) {
                    // Si falla la consulta de impacto, se pide confirmación básica.
                }

                if (confirm(mensaje)) {
                    form.dataset.confirmado = '1';
                    form.submit();
                }
            });
        });
    </script>
</x-layout>
