<x-layout title='PostUDO || Postgrados'>
    <section class="main-content-section page-content">
        {{-- Título de la sección --}}
        <div class="content_texto_bienvenida">
            <label>Listado de Secciones Asignadas a {{ $materia->nombre }}</label>
        </div>

        {{-- Botón para crear un nuevo postgrado --}}
        <div class="action-buttons-container">
            {{-- Se asume que la ruta para crear un postgrado es 'administrador.gestion_postgrado.create' --}}
            <a href="{{ route('administrador.gestionseccion.create', $materia->codigo_materia) }}" class="button_body">
                <i class="fa-solid fa-plus icon-left"></i> Crear Nueva Sección
            </a>
        </div>
        {{-- Tabla para mostrar los postgrados --}}
        <table>
            <thead>
                <tr>
                    <th>Nro Seccion</th>
                    <th>Codigo Materia</th>
                    <th>Duracion</th>
                    <th>Cedula de Profesor Asignado</th>
                    <th>Aula Asignada</th>
                    <th>Cupos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                {{--
                    Bucle @forelse para iterar sobre la colección de     $postgrados.
                    Muestra una fila por cada postgrado.
                --}}
                @forelse ($secciones as $seccion)
                    <tr>
                        <td>{{ $seccion->nro_seccion }}</td>
                        <td>{{ $seccion->codigo_materia }}</td>
                        <td>{{ $seccion->hora_inicio }}-{{ $seccion->hora_fin }}</td>
                        <td>{{ $seccion->cedula_docente }}</td>
                        <td>{{ $seccion->aula }}</td>
                        <td>{{ $seccion->cupo_actual }}-{{ $seccion->cupo_maximo }}</td>
                        

                        {{-- Columna de acciones con botones para Editar y Eliminar --}}
                        <td class="table-actions">
                            {{-- Botón de Editar --}}
                            {{-- Se asume que la ruta para editar es 'administrador.gestion_postgrado.edit' --}}
                            <a href="{{ route('administrador.gestionseccion.edit', $seccion->nro_seccion) }}" class="button_body" title="Editar">
                                <i class="fas fa-pencil-alt"></i>
                            </a>

                            {{-- Botón de Eliminar (usando un formulario para solicitudes DELETE) --}}
                            {{-- Se asume que la ruta para eliminar es 'administrador.gestion_postgrado.destroy' --}}
                            <form action="{{ route('administrador.gestionseccion.destroy', $seccion->nro_seccion) }}" method="POST" class="inline-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button_body" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px;">No hay materias registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
</x-layout>
