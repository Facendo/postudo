<x-layout title='PostUDO || Gestión de Notas'>
    <section class="main-content-section page-content">
        <div class="content_texto_bienvenida">
            <label>Gestión de Notas por Materia y Sección</label>
        </div>

        <div class="filter-section" style="margin-bottom: 20px; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9;">
            <h3 style="margin-top: 0; margin-bottom: 15px; color: #333;">Selecciona Materia y Sección para Gestionar Notas</h3>
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
                <button type="button" id="consultar_notas_button" class="button_body" style="padding: 10px 20px; white-space: nowrap;">
                    <i class="fa-solid fa-search icon-left"></i> Consultar Notas
                </button>
            </div>
        </div>

        {{-- El contenedor de la tabla de gestión de notas se ocultará inicialmente --}}
        <div id="grades_management_container" style="display: none;">
            <div class="table-container" style="overflow-x: auto;">
                <table style="min-width: 950px;"> {{-- Aumentado min-width para acomodar la nueva columna --}}
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Cédula</th> {{-- Nueva columna para la Cédula --}}
                            {{-- Encabezados de asignación generados dinámicamente --}}
                            <th class="assignment-header" data-assignment-id="1" data-percentage="15">Asig 1 (15%)</th>
                            <th class="assignment-header" data-assignment-id="2" data-percentage="20">Asig 2 (20%)</th>
                            <th class="assignment-header" data-assignment-id="3" data-percentage="30">Asig 3 (30%)</th>
                            <th class="assignment-header" data-assignment-id="4" data-percentage="35">Asig 4 (35%)</th>
                            {{-- Agrega más encabezados de asignación dinámicamente según las evaluaciones obtenidas --}}
                            <th>Nota Final</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Este bloque se llenaría con los datos filtrados de estudiantes y sus notas desde el backend --}}
                        {{-- Por ahora, se utilizan datos de ejemplo --}}
                        @php
                            // Simulando datos de estudiantes (en una aplicación real, esto provendría de tu controlador)
                            $students = [
                                (object)['id' => 1, 'nombre' => 'Juan', 'apellido' => 'Pérez', 'cedula' => 'V-12345678'],
                                (object)['id' => 2, 'nombre' => 'María', 'apellido' => 'García', 'cedula' => 'E-87654321'],
                                (object)['id' => 3, 'nombre' => 'Carlos', 'apellido' => 'López', 'cedula' => 'V-11223344'],
                                (object)['id' => 4, 'nombre' => 'Ana', 'apellido' => 'Martínez', 'cedula' => 'V-22334455'],
                            ];

                            // Simulando datos de notas (en una aplicación real, esto provendría de tu controlador)
                            // Las claves deben coincidir con los IDs de asignación, y los valores son student_id => grade
                            // Las notas ahora están en base 0-10, sin decimales en los ejemplos
                            $grades = [
                                '1' => [1 => 9, 2 => 10, 3 => 8, 4 => 10],
                                '2' => [1 => 8, 2 => 8, 3 => 9, 4 => 9],
                                '3' => [1 => 6, 2 => 9, 3 => 10, 4 => 7], 
                                '4' => [1 => 5, 2 => 6, 3 => 7, 4 => 8],
                            ];

                            // Definición de las asignaciones con sus porcentajes para el cálculo
                            $assignments_config = [
                                (object)['id' => 1, 'percentage' => 15],
                                (object)['id' => 2, 'percentage' => 20],
                                (object)['id' => 3, 'percentage' => 30],
                                (object)['id' => 4, 'percentage' => 35],
                            ];
                        @endphp

                        @forelse ($students as $student)
                            <tr>
                                <td>{{ $student->nombre }} {{ $student->apellido }}</td> {{-- Solo nombre y apellido --}}
                                <td>{{ $student->cedula }}</td> {{-- Cédula en columna separada --}}
                                @php
                                    $final_grade = 0;
                                @endphp
                                {{-- Recorre las asignaciones dinámicamente --}}
                                @foreach ($assignments_config as $assignment)
                                    @php
                                        $assignmentId = $assignment->id;
                                        $percentage = $assignment->percentage;
                                        $grade = $grades[$assignmentId][$student->id] ?? 0;
                                        // Calcula la contribución de esta asignación a la nota final (base 10)
                                        $final_grade += ($grade / 10) * ($percentage / 100) * 10;
                                    @endphp
                                    <td>
                                        <input 
                                            type="number" 
                                            class="grade-input form-input" 
                                            value="{{ $grade }}" 
                                            min="0" 
                                            max="10" {{-- Las notas individuales ahora son de 0 a 10 --}}
                                            step="1" {{-- Importante: Permite solo números enteros --}}
                                            data-student-id="{{ $student->id }}"
                                            data-assignment-id="{{ $assignmentId }}"
                                            style="width: 70px; text-align: center; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"
                                            onchange="validateGradeInput(this)" {{-- Llama a la función de validación --}}
                                        >
                                    </td>
                                @endforeach
                                <td class="final-grade-cell" data-student-id="{{ $student->id }}" style="font-weight: bold; text-align: center;">
                                    {{ number_format($final_grade, 2) }} {{-- Muestra la nota final con 2 decimales --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 20px;">No hay estudiantes o evaluaciones para la selección actual.</td> {{-- Colspan ajustado --}}
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="form-actions" style="justify-content: center; margin-top: 20px;">
                <button type="button" id="save_grades_button" class="submit-button">
                    <i class="fa-solid fa-save icon-left"></i> Guardar Notas
                </button>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const consultarNotasButton = document.getElementById('consultar_notas_button');
            const gradesManagementContainer = document.getElementById('grades_management_container');
            const materiaSelect = document.getElementById('materia_select');
            const seccionSelect = document.getElementById('seccion_select');
            const saveGradesButton = document.getElementById('save_grades_button');

            // Store the initial HTML content of the grades management container
            // This is crucial because when you set innerHTML to "Cargando notas...",
            // it overwrites the table content.
            const initialGradesContainerHTML = gradesManagementContainer.innerHTML;

            // Define assignment percentages for client-side calculation (should match backend)
            const assignmentPercentages = {
                '1': 15, // Asig 1 (15%)
                '2': 20, // Asig 2 (20%)
                '3': 30, // Asig 3 (30%)
                '4': 35, // Asig 4 (35%)
                // Add more assignments here as needed
            };

            // Function to validate the grade input
            window.validateGradeInput = function(inputElement) {
                let grade = parseFloat(inputElement.value);
                
                // If the input is empty or not a number, treat it as 0 for calculations but allow empty display
                if (isNaN(grade)) {
                    grade = 0; // Use 0 for calculation if not a number
                    inputElement.value = ''; // Clear the input if it's not a valid number
                } else if (grade < 0) {
                    inputElement.value = 0;
                    grade = 0;
                } else if (grade > 10) { 
                    inputElement.value = 10;
                    grade = 10;
                } else {
                    // Ensure it's an integer
                    inputElement.value = Math.round(grade);
                    grade = Math.round(grade);
                }
                // Update the final grade after validation
                updateFinalGrade(inputElement);
            };

            window.updateFinalGrade = function(inputElement) {
                const studentId = inputElement.dataset.studentId;
                const row = inputElement.closest('tr');
                let calculatedFinalGrade = 0;

                // Iterate over all grade inputs for the current student row
                row.querySelectorAll('.grade-input').forEach(gradeInput => {
                    const assignmentId = gradeInput.dataset.assignmentId;
                    // Ensure the value is a number, default to 0 if empty or invalid
                    const grade = parseFloat(gradeInput.value) || 0; 
                    const percentage = assignmentPercentages[assignmentId];

                    if (percentage !== undefined) {
                        // Formula: (grade / 10) * (percentage / 100) * 10; 
                        calculatedFinalGrade += (grade / 10) * (percentage / 100) * 10;
                    }
                });

                // Update the final grade cell for this student
                const finalGradeCell = row.querySelector(`.final-grade-cell[data-student-id="${studentId}"]`);
                if (finalGradeCell) {
                    finalGradeCell.textContent = calculatedFinalGrade.toFixed(2); // Display with 2 decimal places
                }
            };

            consultarNotasButton.addEventListener('click', function() {
                // Validation to ensure both a course and a section have been selected
                if (materiaSelect.value === "" || seccionSelect.value === "") {
                    // A modal or UI message should be used here instead of alert()
                    alert("Por favor, selecciona una materia y una sección para consultar las notas.");
                    return; // Stop execution if no selections have been made
                }

                // Show loading message and make container visible
                gradesManagementContainer.innerHTML = '<p style="text-align: center; padding: 20px;">Cargando notas...</p>';
                gradesManagementContainer.style.display = 'block';

                setTimeout(() => {
                    // Restore the initial table HTML content
                    gradesManagementContainer.innerHTML = initialGradesContainerHTML;
                    // Ensure the container remains visible
                    gradesManagementContainer.style.display = 'block';

                    // After restoring the HTML, ensure initial final grades are calculated for loaded data
                    // This is especially important if data is dynamically loaded via AJAX
                    document.querySelectorAll('.grade-input').forEach(input => {
                        // Trigger the update for each input to calculate final grades when table is displayed
                        // Also apply validation to initial values in case they are outside the new 0-10 range
                        validateGradeInput(input);
                    });


                    // En una aplicación real, una llamada AJAX al backend iría aquí
                    // para obtener los estudiantes y sus notas para el curso y la sección seleccionados.
                    // También obtendría el plan de evaluación (asignaciones) para construir dinámicamente los encabezados de la tabla.

                    // Ejemplo de cómo podrías obtener y renderizar (conceptual, requiere endpoint de backend):
                    /*
                    fetch(`/api/grades?materia=${materiaSelect.value}&seccion=${seccionSelect.value}`)
                        .then(response => response.json())
                        .then(data => {
                            // Clear existing tbody
                            const tbody = gradesManagementContainer.querySelector('tbody');
                            tbody.innerHTML = ''; 

                            // Assuming 'data' contains students and their grades, and evaluation assignments
                            data.students.forEach(student => {
                                const row = document.createElement('tr');
                                let studentNameCell = document.createElement('td');
                                studentNameCell.textContent = `${student.nombre} ${student.apellido}`; 
                                row.appendChild(studentNameCell);

                                let studentCedulaCell = document.createElement('td'); 
                                studentCedulaCell.textContent = student.cedula; 
                                row.appendChild(studentCedulaCell);

                                let studentFinalGrade = 0; 

                                data.assignments.forEach(assignment => {
                                    let gradeCell = document.createElement('td');
                                    let gradeInput = document.createElement('input');
                                    gradeInput.type = 'number';
                                    gradeInput.className = 'grade-input form-input';
                                    gradeInput.min = '0';
                                    gradeInput.max = '10'; 
                                    gradeInput.step = '1'; // Allow decimals
                                    const studentGrade = student.grades[assignment.id] || 0; 
                                    gradeInput.value = studentGrade;
                                    gradeInput.dataset.studentId = student.id;
                                    gradeInput.dataset.assignmentId = assignment.id;
                                    gradeInput.style.cssText = "width: 70px; text-align: center; padding: 8px; border: 1px solid #ccc; border-radius: 4px;";
                                    gradeInput.onchange = () => validateGradeInput(gradeInput); // Attach validation event listener
                                    gradeCell.appendChild(gradeInput);
                                    row.appendChild(gradeCell);

                                    // Add to final grade calculation (base 10)
                                    studentFinalGrade += (studentGrade / 10) * (assignment.porcentaje / 100) * 10;
                                });

                                // Add final grade cell
                                const finalCell = document.createElement('td');
                                finalCell.className = 'final-grade-cell';
                                finalCell.dataset.studentId = student.id;
                                finalCell.style.cssText = "font-weight: bold; text-align: center;";
                                finalCell.textContent = studentFinalGrade.toFixed(2);
                                row.appendChild(finalCell);

                                tbody.appendChild(row);
                            });

                            // Update table headers dynamically
                            const theadRow = gradesManagementContainer.querySelector('thead tr');
                            // Remove existing dynamic headers (excluding 'Estudiante', 'Cédula', and 'Nota Final')
                            theadRow.querySelectorAll('.assignment-header').forEach(header => header.remove());
                            data.assignments.forEach(assignment => {
                                let th = document.createElement('th');
                                th.textContent = `${assignment.titulo} (${assignment.porcentaje}%)`; 
                                th.className = 'assignment-header';
                                th.dataset.assignmentId = assignment.id; 
                                th.dataset.percentage = assignment.porcentaje; 
                                theadRow.insertBefore(th, theadRow.querySelector('th:last-child')); // Insert before Final Grade
                            });

                            // gradesManagementContainer.style.display = 'block'; // Already visible
                        })
                        .catch(error => {
                            console.error('Error fetching grades:', error);
                            gradesManagementContainer.innerHTML = '<p style="text-align: center; padding: 20px; color: red;">Error al cargar las notas. Intente de nuevo.</p>';
                        });
                    */
                }, 500); // Simulate network delay
            });

            saveGradesButton.addEventListener('click', function() {
                const gradesToSave = [];
                document.querySelectorAll('.grade-input').forEach(input => {
                    const studentId = input.dataset.studentId;
                    const assignmentId = input.dataset.assignmentId;
                    const grade = input.value;
                    gradesToSave.push({
                        student_id: studentId,
                        assignment_id: assignmentId, // Esto sería el id_evaluacion
                        grade: grade
                    });
                });

                console.log("Notas a guardar:", gradesToSave);
                // Aquí, enviarías 'gradesToSave' a tu backend usando una solicitud AJAX (ej., fetch o Axios)
                // fetch('/api/save-grades', {
                //     method: 'POST',
                //     headers: {
                //         'Content-Type': 'application/json',
                //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Si usas token CSRF de Laravel
                //     },
                //     body: JSON.stringify(gradesToSave)
                // })
                // .then(response => response.json())
                // .then(data => {
                //     alert('Notas guardadas exitosamente!');
                //     console.log(data);
                // })
                // .catch(error => {
                //     alert('Error al guardar las notas.');
                //     console.error('Error saving grades:', error);
                // });
                alert('Funcionalidad de guardar notas (simulado). Revisa la consola para ver los datos.');
            });
        });
    </script>
</x-layout>
