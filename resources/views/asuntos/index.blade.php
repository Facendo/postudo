<x-layout title='PostUDO || Asuntos'>
    <section class="main-content-section page-content">
        {{-- Título de la sección --}}
        <div class="content_texto_bienvenida">
            <label>Listado de Asuntos</label>
        </div>

        {{-- Botón para crear un nuevo asunto --}}
        <div class="action-buttons-container">
            {{-- Se asume que la ruta para crear un asunto es 'asuntos.create' --}}
            <a href="{{ route('asuntos.create') }}" class="button_body">
                <i class="fa-solid fa-plus icon-left"></i> Nuevo Asunto
            </a>
        </div>
        {{-- Tabla para mostrar los asuntos --}}
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                {{--
                    Bucle @forelse para iterar sobre la colección de $asuntos.
                    Muestra una fila por cada asunto.
                --}}
                @forelse ($asuntos as $asunto)
                    <tr>
                        <td>{{ $asunto->id }}</td>
                        <td>{{ $asunto->nombre }}</td>
                        <td>{{ $asunto->descripcion }}</td>
                        
                        
                        {{-- Columna de acciones con botones para Editar y Eliminar --}}
                        <td class="table-actions">
                            {{-- Botón de Editar --}}
                            {{-- Se asume que la ruta para editar es 'asuntos.edit' --}}
                            <a href="{{ route('asuntos.edit', $asunto->id) }}" class="button_body" title="Editar">
                                <i class="fas fa-pencil-alt"></i>
                            </a>

                            {{-- Botón de Eliminar (usando un formulario para solicitudes DELETE) --}}
                            {{-- Se asume que la ruta para eliminar es 'asuntos.destroy' --}}
                            <form action="{{ route('asuntos.destroy', $asunto->id) }}" method="POST" class="inline-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button_body" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    {{-- Este bloque se muestra si la colección $asuntos está vacía.
                        El colspan se ajusta a 9 para coincidir con el número de columnas.
                    --}}
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 20px;">No hay asuntos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
</x-layout>
