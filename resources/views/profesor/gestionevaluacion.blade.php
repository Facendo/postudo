<x-layout title='PostUDO || Gestión de Evaluaciones'>
    <section class="main-content-section page-content">
        <div class="content_texto_bienvenida">
            <label>Gestión de Planes de Evaluación</label>
        </div>

        <div class="filter-section" style="margin-bottom: 20px; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9;">
            <h3 style="margin-top: 0; margin-bottom: 15px; color: #333;">Selecciona la Materia y Sección para ver el Plan de Evaluación</h3>
            <div class="form-group" style="display: flex; gap: 20px; margin-bottom: 15px; align-items: flex-end;">
                <div style="flex: 1;">
                    <label for="materia_select" class="form-label">Materia</label>
                    <select id="materia_select" class="form-select" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="">Selecciona una Materia</option>
                        <option value="matematica">Matemática III</option>
                        <option value="programacion">Programación Avanzada</option>
                        <option value="ingles">Inglés Técnico</option>
                        <option value="fisica">Física II</option>
                        {{-- Add more course options here, these are just examples --}}
                    </select>
                </div>
                <div style="flex: 1;">
                    <label for="seccion_select" class="form-label">Sección</label>
                    <select id="seccion_select" class="form-select" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="">Selecciona una Sección</option>
                        <option value="A">Sección A</option>
                        <option value="B">Sección B</option>
                        <option value="C">Sección C</option>
                        {{-- Add more section options here --}}
                    </select>
                </div>
                <button type="button" id="consultar_evaluacion_button" class="button_body" style="padding: 10px 20px; white-space: nowrap;">
                    <i class="fa-solid fa-search icon-left"></i> Consultar Plan de Evaluación
                </button>
            </div>
        </div>

        {{-- The evaluation table container will be hidden initially --}}
        <div id="evaluations_list_container" style="display: none;">
            <div class="new-student-button-container" style="margin-top: 20px;">
                {{-- Ensure this route is correct for creating a new evaluation --}}
                <a href="{{ route('profesor.gestionevaluacion.create') }}" class="button_body">
                    <i class="fa-solid fa-plus icon-left"></i> Agregar Evaluación
                </a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th> {{-- New column for ID --}}
                        <th>Título</th>
                        <th>Metodología</th>
                        <th>Porcentaje</th>
                        <th>Fecha</th> {{-- Assuming this field exists for an evaluation plan --}}
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- This block would be populated with filtered evaluations from the backend --}}
                    {{-- For now, an example loop is used; you'll need to pass the evaluations from your controller --}}
                    @forelse ($evaluaciones as $evaluacion)
                        <tr>
                            <td>{{ $evaluacion->id_evaluacion }}</td> {{-- Displaying the evaluation ID --}}
                            <td>{{ $evaluacion->titulo }}</td>
                            <td>{{ $evaluacion->metodologia }}</td>
                            <td>{{ $evaluacion->porcentaje }}%</td>
                            {{-- Ensure $evaluacion->fecha exists and has the correct format in your model --}}
                            <td>{{ \Carbon\Carbon::parse($evaluacion->fecha ?? '2000-01-01')->format('d/m/Y') }}</td> 
                            <td class="table-actions">
                                {{-- Edit button --}}
                                <a href="#" class="button_body" title="Editar">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>

                                {{-- Delete button (using a form for DELETE requests) --}}
                                <form action="#" method="POST" class="inline-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button_body" title="Eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar esta evaluación?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px;">No hay evaluaciones registradas para la materia y sección seleccionadas.</td> {{-- Updated colspan to 6 --}}
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const consultarEvaluacionButton = document.getElementById('consultar_evaluacion_button');
            const evaluationsListContainer = document.getElementById('evaluations_list_container');
            const materiaSelect = document.getElementById('materia_select');
            const seccionSelect = document.getElementById('seccion_select'); // Get the section select element

            consultarEvaluacionButton.addEventListener('click', function() {
                // Validation to ensure both a course and a section have been selected
                if (materiaSelect.value === "" || seccionSelect.value === "") {
                    // A modal or UI message should be used here instead of alert()
                    // For this example, alert() is kept for simplicity.
                    alert("Por favor, selecciona una materia y una sección para consultar el plan de evaluación.");
                    return; // Stop execution if no selections have been made
                }

                // In a real application, an AJAX call to the backend would go here
                // to get the evaluation plans filtered by course and section.
                // Once data is received, you would update the table's tbody.

                // For now, only make the table container visible
                evaluationsListContainer.style.display = 'block';

                // If you wanted to simulate a "loading" message and then show:
                // evaluationsListContainer.innerHTML = '<p style="text-align: center;">Cargando plan de evaluación...</p>';
                // setTimeout(() => {
                //     // The logic to render evaluations would go here
                //     // evaluationsListContainer.innerHTML = '... table with data ...';
                //     evaluationsListContainer.style.display = 'block'; // Ensure it remains visible
                // }, 1000);
            });
        });
    </script>
</x-layout>
