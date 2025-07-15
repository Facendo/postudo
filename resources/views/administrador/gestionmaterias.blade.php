<x-layout title='PostUDO || Gestión de Materias'>
    <section class="main-content-section page-content">
        <div class="content_texto_bienvenida">
            <label>Listado de Materias</label>
        </div>

        <div class="new-student-button-container">
            {{-- Cambia la ruta para crear una nueva materia --}}
            <a href="{{ route('administrador.gestionmaterias.create') }}" class="button_body">
                <i class="fa-solid fa-plus icon-left"></i> Nueva Materia
            </a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Código Materia</th>
                    <th>Nombre</th>
                    <th>Carrera</th>
                    <th>Especialidad</th>
                    <th>Sección</th>
                    <th>Prelación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                {{-- La variable debe ser 'materias' (en plural) si el controlador la pasa así --}}
                @forelse ($materias as $materia)
                    <tr>
                        <td>{{ $materia->codigo_materia }}</td>
                        <td>{{ $materia->nombre}}</td>
                        <td>{{ $materia->nombre_carrera }}</td>
                        <td>{{ $materia->nombre_especialidad }}</td>
                        <td>{{ $materia->nro_seccion }}</td>
                        <td>{{ $materia->prelacion }}</td>

                        <td class="table-actions">
                            {{-- Botón de Editar --}}
                            {{-- Asegúrate de que tu ruta sea 'materias.edit' y acepte el modelo Materias --}}
                            <a href="{{ route('administrador.gestionmaterias.edit', $materia) }}" class="button_body" title="Editar">
                                <i class="fas fa-pencil-alt"></i>
                            </a>

                            {{-- Botón de Eliminar (usando un formulario para solicitudes DELETE) --}}
                            {{-- Asegúrate de que tu ruta sea 'materias.destroy' y acepte el modelo Materias --}}
                            <form action="{{ route('administrador.gestionmaterias.destroy', $materia) }}" method="POST" class="inline-form">
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
                        <td colspan="6" style="text-align: center; padding: 20px;">No hay materias registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
</x-layout>