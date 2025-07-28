<x-layout title='PostUDO || Profesores'>
    <section class="main-content-section page-content">
        <div class="content_texto_bienvenida">
            <label>Listado de Profesores</label>
        </div>


        
        <div class="action-buttons-container">
            {{-- Asegúrate de que la ruta sea correcta para crear un nuevo profesor --}}
            {{-- Se asume que la ruta es 'administrador.gestion_profesor.create' --}}
            {{-- Botón para añadir un nuevo profesor, la ruta debe ser ajustada a la de registro de profesores --}}
            <a href="{{route('administrador.gestion_profesor.create')}}" class="button_body">
                <i class="fa-solid fa-plus icon-left"></i> Nuevo Profesor
            </a>
        </div>

        <table>
            <thead>
                <tr>
                    {{-- Encabezados de la tabla para los atributos del profesor --}}
                    <th>Cédula</th>
                    <th>Nro. Sección</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Edad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                {{-- Bucle para iterar sobre la colección de profesores --}}
                @forelse ($profesores as $profesor)
                    <tr>
                        {{-- Muestra los datos de cada profesor --}}
                        <td>{{ $profesor->cedula }}</td>
                        <td>{{ $profesor->nro_seccion }}</td>
                        <td>{{ $profesor->nombre }}</td>
                        <td>{{ $profesor->apellido }}</td>
                        <td>{{ $profesor->telefono }}</td>
                        <td>{{ $profesor->correo }}</td>
                        <td>{{ $profesor->edad }}</td>
                        
                        <td class="table-actions">
                            {{-- Botón de Editar (la ruta debe ser ajustada a la de edición de profesores) --}}
                            <a href="{{route('administrador.gestion_profesor.edit', $profesor)}}" class="button_body " title="Editar">
                                <i class="fas fa-pencil-alt"></i>
                            </a>

                            {{-- Botón de Eliminar (la ruta debe ser ajustada a la de eliminación de profesores) --}}
                            <form action="{{ route('administrador.gestion_profesor.destroy', $profesor) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button_body " title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        {{-- Mensaje si no hay profesores registrados --}}
                        <td colspan="8" style="text-align: center; padding: 20px;">No hay profesores registrados.</td>
                    </tr>
                @endforelse
                
            </tbody>
        </table>
    </section>
</x-layout>
