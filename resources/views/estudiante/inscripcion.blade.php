<x-layout title='PostUDO || Registro de Estudiante'> {{-- Mantenemos el título original solicitado --}}
    <section class="login-page-container">
        <div class="registration-form">
            <h2 class="registration-form-title">Inscripción de Estudiante a Oferta Académica</h2> {{-- Título más descriptivo --}}

            {{-- Formulario para la inscripción de estudiantes en una sección específica --}}
            <form action="{{ route('estudiante.inscribir') }}" method="POST">
                @csrf {{-- Token CSRF para protección contra ataques --}}

                {{-- Campo para seleccionar la Especialidad/Postgrado --}}
                <div class="form-group">
                    <label for="id_postgrado" class="form-label">Especialidad/Postgrado</label>
                    <select id="id_postgrado" name="id_postgrado" class="form-input" required>
                        <option value="">Selecciona una Especialidad</option>
                        @forelse($postgrados as $postgrado)
                            <option value="{{ $postgrado->id_postgrado }}" {{ old('id_postgrado') == $postgrado->id_postgrado ? 'selected' : '' }}>
                                {{ $postgrado->nombre }}
                            </option>
                        @empty
                            <option value="" disabled>No hay Postgrados registrados.</option>
                        @endforelse
                    </select>
                    @error('id_postgrado')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Campo para seleccionar la Cohorte --}}
                <div class="form-group">
                    <label for="codigo_cohorte" class="form-label">Cohorte</label>
                    <select id="codigo_cohorte" name="codigo_cohorte" class="form-input" required disabled>
                        <option value="">Selecciona una Cohorte</option>
                    </select>
                    @error('codigo_cohorte')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Campo para seleccionar la Materia --}}
                <div class="form-group">
                    <label for="codigo_materia" class="form-label">Materia</label>
                    <select id="codigo_materia" name="codigo_materia" class="form-input" required disabled>
                        <option value="">Selecciona una Materia</option>
                    </select>
                    @error('codigo_materia')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Campo para seleccionar la Sección --}}
                <div class="form-group">
                    <label for="nro_seccion" class="form-label">Sección</label>
                    <select id="nro_seccion" name="nro_seccion" class="form-input" required disabled>
                        <option value="">Selecciona una Sección</option>
                    </select>
                    @error('nro_seccion')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Inscribirse</button>
                </div>
            </form>
        </div>
    </section>

    <script>
        // Datos pasados desde Laravel Blade como JSON
        // **IMPORTANTE:** Asegúrate de que estas variables (`$postgrados`, `$cohortes`, `$materias`, `$secciones`)
        // estén disponibles en tu vista Blade cuando se renderice, pasadas desde tu controlador.
        // Ejemplo en el controlador: `return view('tu_vista', compact('postgrados', 'cohortes', 'materias', 'secciones'));`
        const allPostgrados = @json($postgrados ?? []);
        const allCohortes = @json($cohortes ?? []);
        const allMaterias = @json($materias ?? []);
        const allSecciones = @json($secciones ?? []);

        // Elementos del DOM
        const postgradoSelect = document.getElementById('id_postgrado');
        const cohorteSelect = document.getElementById('codigo_cohorte');
        const materiaSelect = document.getElementById('codigo_materia');
        const seccionSelect = document.getElementById('nro_seccion');

        // Función para resetear y deshabilitar un select
        function resetAndDisableSelect(selectElement, defaultText) {
            selectElement.innerHTML = `<option value="">${defaultText}</option>`;
            selectElement.disabled = true;
        }

        // Función para poblar un select
        function populateSelect(selectElement, items, idKey, nameKey, defaultText) {
            selectElement.innerHTML = `<option value="">${defaultText}</option>`; // Siempre iniciar con la opción por defecto
            if (items.length > 0) {
                items.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item[idKey];
                    option.textContent = item[nameKey]; // Usar la clave de nombre para el texto visible
                    selectElement.appendChild(option);
                });
                selectElement.disabled = false; // Habilitar el select si hay opciones
            } else {
                // Mostrar mensaje de "No hay disponibles" y deshabilitar si no hay ítems
                selectElement.innerHTML = `<option value="" disabled>No hay ${defaultText.toLowerCase().replace('selecciona una ', '')} disponibles.</option>`;
                selectElement.disabled = true;
            }
        }

        // Event Listener para Postgrado
        postgradoSelect.addEventListener('change', function() {
            const selectedPostgradoId = this.value;
            
            // Resetear y deshabilitar los selects subsiguientes
            resetAndDisableSelect(cohorteSelect, 'Selecciona una Cohorte');
            resetAndDisableSelect(materiaSelect, 'Selecciona una Materia');
            resetAndDisableSelect(seccionSelect, 'Selecciona una Sección');

            if (selectedPostgradoId) {
                // Filtrar cohortes por el postgrado seleccionado
                const filteredCohortes = allCohortes.filter(cohorte => cohorte.codigo_postgrado == selectedPostgradoId);
                // Usamos 'codigo_cohorte' tanto para el valor como para el texto visible
                populateSelect(cohorteSelect, filteredCohortes, 'codigo_cohorte', 'codigo_cohorte', 'Selecciona una Cohorte');
            }
        });

        // Event Listener para Cohorte
        cohorteSelect.addEventListener('change', function() {
            const selectedCohorteId = this.value;

            // Resetear y deshabilitar los selects subsiguientes
            resetAndDisableSelect(materiaSelect, 'Selecciona una Materia');
            resetAndDisableSelect(seccionSelect, 'Selecciona una Sección');

            if (selectedCohorteId) {
                // Filtrar materias por la cohorte seleccionada
                const filteredMaterias = allMaterias.filter(materia => materia.codigo_cohorte == selectedCohorteId);
                // Usamos 'codigo_materia' para el valor y 'nombre' para el texto visible
                populateSelect(materiaSelect, filteredMaterias, 'codigo_materia', 'nombre', 'Selecciona una Materia');
            }
        });

        // Event Listener para Materia
        materiaSelect.addEventListener('change', function() {
            const selectedMateriaId = this.value;

            // Resetear y deshabilitar el select de secciones
            resetAndDisableSelect(seccionSelect, 'Selecciona una Sección');

            if (selectedMateriaId) {
                // Filtrar secciones por la materia seleccionada
                const filteredSecciones = allSecciones.filter(seccion => seccion.codigo_materia == selectedMateriaId);
                
                // Mapeamos las secciones para crear un texto descriptivo para la opción
                // Incluye el número de sección, aula y cupo.
                const mappedSecciones = filteredSecciones.map(s => ({
                    nro_seccion: s.nro_seccion, // Usamos nro_seccion como ID/valor
                    displayText: `Sección ${s.nro_seccion} (Aula: ${s.aula}, Cupo: ${s.cupo_actual}/${s.cupo_maximo})` // Texto visible
                }));
                populateSelect(seccionSelect, mappedSecciones, 'nro_seccion', 'displayText', 'Selecciona una Sección');
            }
        });

        // Opcional: Lógica para re-seleccionar valores antiguos si la validación falla
        // Esto es útil si el formulario se recarga debido a un error de validación del servidor
        document.addEventListener('DOMContentLoaded', () => {
            const oldPostgradoId = "{{ old('id_postgrado') }}";
            const oldCohorteId = "{{ old('codigo_cohorte') }}";
            const oldMateriaId = "{{ old('codigo_materia') }}";
            const oldSeccionNro = "{{ old('nro_seccion') }}";

            if (oldPostgradoId) {
                postgradoSelect.value = oldPostgradoId;
                // Dispara el evento 'change' para cargar las cohortes correspondientes
                postgradoSelect.dispatchEvent(new Event('change'));

                // Usamos un pequeño retraso para asegurar que el DOM se actualice antes de seleccionar el siguiente.
                // En un entorno de producción con muchas opciones o red lenta, esto podría necesitar un manejo más robusto.
                setTimeout(() => {
                    if (oldCohorteId) {
                        cohorteSelect.value = oldCohorteId;
                        cohorteSelect.dispatchEvent(new Event('change'));

                        setTimeout(() => {
                            if (oldMateriaId) {
                                materiaSelect.value = oldMateriaId;
                                materiaSelect.dispatchEvent(new Event('change'));

                                setTimeout(() => {
                                    if (oldSeccionNro) {
                                        seccionSelect.value = oldSeccionNro;
                                    }
                                }, 100);
                            }
                        }, 100);
                    }
                }, 100);
            }
        });

    </script>
</x-layout>
