<x-layout title='PostUDO || Listado de Estudiantes por Materia y Sección'>
    <section class="main-content-section page-content">
        <div class="content_texto_bienvenida">
            <label>Consulta de Estudiantes por Materia y Sección</label>
        </div>

        <div class="filter-section" style="margin-bottom: 20px; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9;">
            <h3 style="margin-top: 0; margin-bottom: 15px; color: #333;">Selecciona Materia y Sección</h3>
            <div class="form-group" style="display: flex; gap: 20px; margin-bottom: 15px; align-items: flex-end;">
                <div style="flex: 1;">
                    <label for="materia_select" class="form-label">Materia</label>
                    <select id="materia_select" class="form-select" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="">Selecciona una Materia</option>
                        <option value="matematica">Matemática III</option>
                        <option value="programacion">Programación Avanzada</option>
                        <option value="ingles">Inglés Técnico</option>
                        <option value="fisica">Física II</option>
                        {{-- Agrega más opciones de materias aquí --}}
                    </select>
                </div>
                <div style="flex: 1;">
                    <label for="seccion_select" class="form-label">Sección</label>
                    <select id="seccion_select" class="form-select" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="">Selecciona una Sección</option>
                        <option value="A">Sección A</option>
                        <option value="B">Sección B</option>
                        <option value="C">Sección C</option>
                        {{-- Agrega más opciones de secciones aquí --}}
                    </select>
                </div>
                <button type="button" id="consultar_button" class="button_body" style="padding: 10px 20px; white-space: nowrap;">
                    <i class="fa-solid fa-search icon-left"></i> Consultar Lista
                </button>
            </div>
        </div>

        {{-- La tabla de estudiantes se ocultará inicialmente --}}
        <div id="student_list_container" style="display: none;">

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
                        {{-- La columna de Acciones ha sido eliminada --}}
                    </tr>
                </thead>
                <tbody>
                    {{-- Este bloque se llenaría con los estudiantes filtrados desde el backend --}}
                    @forelse ($estudiantes as $estudiante)
                        <tr>
                            <td>{{ $estudiante->cedula }}</td>
                            <td>{{ $estudiante->nombre }}</td>
                            <td>{{ $estudiante->apellido }}</td>
                            <td>{{ $estudiante->carrera }}</td>
                            <td>{{ $estudiante->especialidad }}</td>
                            <td>{{ $estudiante->correo }}</td>
                            <td>{{ $estudiante->edad }}</td>
                            {{-- Las acciones de editar y eliminar han sido eliminadas --}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 20px;">No hay estudiantes registrados para la selección actual.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const consultarButton = document.getElementById('consultar_button');
            const studentListContainer = document.getElementById('student_list_container');
            const materiaSelect = document.getElementById('materia_select');
            const seccionSelect = document.getElementById('seccion_select');

            consultarButton.addEventListener('click', function() {
                // Validación simple para asegurar que se ha seleccionado algo
                if (materiaSelect.value === "" || seccionSelect.value === "") {
                    // Reemplazar alert() con un mensaje en la UI o un modal si es necesario
                    // Para este ejemplo, mantendremos alert() como una demostración rápida.
                    alert("Por favor, selecciona una materia y una sección para consultar.");
                    return; // Detiene la ejecución si no se han hecho selecciones
                }

                // Aquí es donde en una aplicación real harías una llamada AJAX al backend
                // para obtener los estudiantes filtrados basándote en
                // materiaSelect.value y seccionSelect.value.
                // Una vez que recibes los datos, actualizarías el tbody de la tabla.

                // Por ahora, solo hacemos visible el contenedor de la tabla
                studentListContainer.style.display = 'block';

                // Si quisieras simular un mensaje de "cargando" y luego mostrar:
                // studentListContainer.innerHTML = '<p style="text-align: center;">Cargando estudiantes...</p>';
                // setTimeout(() => {
                //     // Aquí iría la lógica para renderizar los estudiantes
                //     // studentListContainer.innerHTML = '... tabla con datos ...';
                //     studentListContainer.style.display = 'block'; // Asegúrate de que siga visible
                // }, 1000);
            });
        });
    </script>
</x-layout>
