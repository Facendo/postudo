<x-layout title='PostUDO || Postgrados'>
    <section class="main-content-section page-content">
        {{-- Título de la sección --}}
        <div class="content_texto_bienvenida">
            <label>Listado de materias Asignadas a {{ $cohorte->nro_de_cohorte }}° cohorte</label>
        </div>

        {{-- Botón para crear un nuevo postgrado --}}
        <div class="new-student-button-container">
            {{-- Se asume que la ruta para crear un postgrado es 'administrador.gestion_postgrado.create' --}}
            <a href="{{ route('administrador.gestioncohorte.create', $cohorte->codigo_cohorte) }}" class="button_body">
                <i class="fa-solid fa-plus icon-left"></i> Nuevo Cohorte
            </a>
        </div>
        {{-- Tabla para mostrar los postgrados --}}
        <table>
            <thead>
                <tr>
                    <th>Codigo Materia</th>
                    <th>Nombre</th>
                    <th>Prelacion</th>
                    <th>Ver Secciones asignadas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                {{--
                    Bucle @forelse para iterar sobre la colección de $postgrados.
                    Muestra una fila por cada postgrado.
                --}}
                @forelse ($materias as $materia)
                    <tr>
                        <td>{{ $materia->codigo_materia }}</td>
                        <td>{{ $materia->nombre }}</td>
                        <td>{{ $materia->prelacion }}</td>
                        <td>{{-- Enlace a la vista de secciones asignadas --}}
                            <a href="{{ route('administrador.gestioncohorte.showdetalles', $materia->codigo_materia) }}" class="button_body" title="Ver Secciones">
                                <i class="fas fa-eye"></i>
                            </a></td>
                        <td>{{-- Enlace a la vista de detalles del postgrado --}}
                            <a href="{{ route('administrador.gestion_postgrado.showdetalles', $postgrado->id_postgrado) }}" class="button_body" title="Ver Detalles">
                                <i class="fas fa-eye"></i>
                            </a></td>

                        {{-- Columna de acciones con botones para Editar y Eliminar --}}
                        <td class="table-actions">
                            {{-- Botón de Editar --}}
                            {{-- Se asume que la ruta para editar es 'administrador.gestion_postgrado.edit' --}}
                            <a href="{{ route('administrador.gestioncohorte.edit', $cohorte->codigo_cohorte) }}" class="button_body" title="Editar">
                                <i class="fas fa-pencil-alt"></i>
                            </a>

                            {{-- Botón de Eliminar (usando un formulario para solicitudes DELETE) --}}
                            {{-- Se asume que la ruta para eliminar es 'administrador.gestion_postgrado.destroy' --}}
                            <form action="{{ route('administrador.gestioncohorte.destroy', $cohorte->codigo_cohorte) }}" method="POST" class="inline-form">
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
                        <td colspan="7" style="text-align: center; padding: 20px;">No hay postgrados registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
</x-layout>
