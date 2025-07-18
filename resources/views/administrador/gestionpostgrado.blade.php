{{--
    Vista para mostrar el listado de Postgrados.
    Adaptada desde la vista de listado de estudiantes.
--}}
<x-layout title='PostUDO || Postgrados'>
    <section class="main-content-section page-content">
        {{-- Título de la sección --}}
        <div class="content_texto_bienvenida">
            <label>Listado de Postgrados</label>
        </div>

        {{-- Botón para crear un nuevo postgrado --}}
        <div class="new-student-button-container">
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
                    <th>Cód. Especialidad</th>
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
                        <td>{{ $postgrado->Id_postgrado }}</td>
                        <td>{{ $postgrado->nombre }}</td>
                        <td>{{ $postgrado->descripcion }}</td>
                        <td>{{ $postgrado->duracion }}</td>
                        <td>{{ $postgrado->codigo_especialidad }}</td>
                        
                        {{-- Columna de acciones con botones para Editar y Eliminar --}}
                        <td class="table-actions">
                            {{-- Botón de Editar --}}
                            {{-- Se asume que la ruta para editar es 'administrador.gestion_postgrado.edit' --}}
                            <a href="{{ route('administrador.gestion_postgrado.edit', $postgrado) }}" class="button_body" title="Editar">
                                <i class="fas fa-pencil-alt"></i>
                            </a>

                            {{-- Botón de Eliminar (usando un formulario para solicitudes DELETE) --}}
                            {{-- Se asume que la ruta para eliminar es 'administrador.gestion_postgrado.destroy' --}}
                            <form action="{{ route('administrador.gestion_postgrado.destroy', $postgrado) }}" method="POST" class="inline-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button_body" title="Eliminar">
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
                        <td colspan="7" style="text-align: center; padding: 20px;">No hay postgrados registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
</x-layout>
