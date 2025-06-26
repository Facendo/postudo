<x-layout title='PostUDO || Inicio'>
        <section class="main-content-section page-content">
            <div class="content_texto_bienvenida">
                <label>Listado de Estudiantes</label>
            </div>

            <div class="new-student-button-container">
                <a href="#" class="button_body">
                <i class="fa-solid fa-plus icon-left"></i> Nuevo Estudiante
            </a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Carrera</th>
                        <th>Especialidad</th>
                        <th>Correo</th>
                        <th>Edad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    
                @forelse ($estudiantes as $estudiante)
                        <tr>
                            <td>{{ $estudiante->cedula }}</td>
                            <td>{{ $estudiante->nombre }}</td>
                            <td>{{ $estudiante->apellido }}</td>
                            <td>{{ $estudiante->carrera }}</td>
                            <td>{{ $estudiante->especialidad }}</td>
                            <td>{{ $estudiante->correo }}</td>
                            <td>{{ $estudiante->edad }}</td>
                          
                            <td class="table-actions">
                                {{-- Botón de Editar --}}
                                <a href="#" class="action-button edit" title="Editar">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>

                                {{-- Botón de Eliminar (usando un formulario para solicitudes DELETE) --}}
                                 <a href="#" class="action-button eliminar" title="Eliminar">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 20px;">No hay estudiantes registrados.</td>
                        </tr>
                    @endforelse
                    
                </tbody>
            </table>
        </section>
    
</x-layout>