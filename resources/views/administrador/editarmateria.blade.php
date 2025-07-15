<x-layout title='PostUDO || Editar Materia'>
    <section class="login-page-container">
        <div class="registration-form">
            <h2 class="registration-form-title">Editar Materia: {{ $materia->nombre }}</h2>

            {{-- Form for subject editing --}}
            {{-- Action points to the update route with the materia's primary key --}}
            <form action="{{ route('administrador.gestionmaterias.update', $materia->codigo_materia) }}" method="POST">
                @csrf
                @method('PUT') {{-- Use PATCH method for updates, Laravel handles this via a hidden input --}}
                
                {{-- Código de Materia (Typically read-only for primary key to avoid accidental changes) --}}
                <div class="form-group">
                    <label for="codigo_materia" class="form-label">Código de Materia</label>
                    <input type="number" id="codigo_materia" name="codigo_materia" class="form-input" 
                           placeholder="Ej: 101" required 
                           value="{{ old('codigo_materia', $materia->codigo_materia) }}">
                    @error('codigo_materia')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nombre de la Materia --}}
                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre de la Materia</label>
                    <input type="text" id="nombre" name="nombre" class="form-input" 
                           placeholder="Ej: Programación Avanzada" required 
                           value="{{ old('nombre', $materia->nombre) }}">
                    @error('nombre')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Carrera Selection --}}
                <div class="form-group">
                    <label for="carrera" class="form-label">Carrera</label>
                    <select id="carrera" name="carrera" class="form-select" required>
                        <option value="">Selecciona la Carrera</option>
                        @php
                            // Determine the slug for the currently saved career name for pre-selection
                            // Example: "Ingeniería Informática" -> "informatica"
                            $currentCareerSlug = strtolower(str_replace(' ', '', $materia->nombre_carrera)); 
                        @endphp
                        <option value="informatica" {{ old('carrera', $currentCareerSlug) == 'informatica' ? 'selected' : '' }}>Ingeniería Informática</option>
                        <option value="civil" {{ old('carrera', $currentCareerSlug) == 'civil' ? 'selected' : '' }}>Ingeniería Civil</option>
                        <option value="electrica" {{ old('carrera', $currentCareerSlug) == 'electrica' ? 'selected' : '' }}>Ingeniería Eléctrica</option>
                        <option value="mecanica" {{ old('carrera', $currentCareerSlug) == 'mecanica' ? 'selected' : '' }}>Ingeniería Mecánica</option>
                        <option value="quimica" {{ old('carrera', $currentCareerSlug) == 'quimica' ? 'selected' : '' }}>Ingeniería Química</option>
                        <option value="administracion" {{ old('carrera', $currentCareerSlug) == 'administracion' ? 'selected' : '' }}>Administración</option>
                        <option value="contaduria" {{ old('carrera', $currentCareerSlug) == 'contaduria' ? 'selected' : '' }}>Contaduría Pública</option>
                        <option value="derecho" {{ old('carrera', $currentCareerSlug) == 'derecho' ? 'selected' : '' }}>Derecho</option>
                        {{-- If you're fetching careers from the database, use this structure:
                        @foreach($carreras as $carreraOption)
                            <option value="{{ strtolower($carreraOption->codigo_carrera) }}"
                                {{ old('carrera', strtolower($materia->nombre_carrera)) == strtolower($carreraOption->codigo_carrera) ? 'selected' : '' }}>
                                {{ $carreraOption->nombre_carrera }}
                            </option>
                        @endforeach
                        --}}
                    </select>
                    @error('carrera')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Especialidad Selection (Dynamically populated by JS) --}}
                <div class="form-group">
                    <label for="especialidad" class="form-label">Especialidad</label>
                    <select id="especialidad" name="codigo_especialidad" class="form-select" required>
                        {{-- This option will be replaced by JS, but ensures something is there --}}
                        <option value="">Selecciona una carrera primero</option>
                        
                        {{-- Add the currently saved specialty as a selected option for initial load --}}
                        @if($materia->nombre_especialidad)
                            <option value="{{ old('codigo_especialidad', $materia->nombre_especialidad) }}" selected>
                                {{ $materia->nombre_especialidad }}
                            </option>
                        @endif
                    </select>
                    @error('codigo_especialidad')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Número de Sección --}}
                <div class="form-group">
                    <label for="nro_seccion" class="form-label">Número de Sección</label>
                    <input type="number" id="nro_seccion" name="nro_seccion" class="form-input" 
                           placeholder="Ej: 1" required 
                           value="{{ old('nro_seccion', $materia->nro_seccion) }}">
                    @error('nro_seccion')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Prelación (Optional) --}}
                <div class="form-group">
                    <label for="prelacion" class="form-label">Prelación (Código Materia)</label>
                    <input type="number" id="prelacion" name="prelacion" class="form-input" 
                           placeholder="Ej: 100 (opcional)" 
                           value="{{ old('prelacion', $materia->prelacion) }}">
                    @error('prelacion')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Submit Button --}}
                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Actualizar Materia</button>
                </div>

            </form>
        </div>
    </section>

    ---

    <script>
        // Define las especialidades por carrera.
        const especialidadesPorCarrera = {
            informatica: [
                "Desarrollo de Software", "Redes y Telecomunicaciones", "Ciberseguridad",
                "Inteligencia Artificial", "Base de Datos"
            ],
            civil: [
                "Estructuras", "Hidráulica", "Vías y Transportes",
                "Construcción", "Ingeniería Sanitaria"
            ],
            electrica: [
                "Sistemas de Potencia", "Control y Automatización",
                "Máquinas Eléctricas", "Electrónica de Potencia"
            ],
            mecanica: [
                "Diseño Mecánico", "Termodinámica y Fluidos",
                "Automatización Industrial", "Manufactura"
            ],
            quimica: [
                "Procesos Industriales", "Bioingeniería", "Petroquímica",
                "Ingeniería Ambiental"
            ],
            administracion: [
                "Gerencia Empresarial", "Recursos Humanos", "Finanzas",
                "Mercadeo", "Operaciones"
            ],
            contaduria: [
                "Auditoría", "Tributaria", "Costos", "Finanzas Corporativas"
            ],
            derecho: [
                "Derecho Penal", "Derecho Civil", "Derecho Laboral",
                "Derecho Mercantil", "Derecho Internacional"
            ]
        };

        const selectCarrera = document.getElementById('carrera');
        const selectEspecialidad = document.getElementById('especialidad');
        
        // Store the original values from the PHP backend for initial load
        // This takes into account 'old' input (from validation error) or the current materia's value
        const initialCareerValue = "{{ old('carrera', $materia->nombre_carrera ? strtolower(str_replace(' ', '', $materia->nombre_carrera)) : '') }}";
        const initialSpecialtyValue = "{{ old('codigo_especialidad', $materia->nombre_especialidad) }}";

        function actualizarEspecialidades() {
            const carreraSeleccionada = selectCarrera.value;
            selectEspecialidad.innerHTML = ''; // Clear current options

            // Add the default option
            const defaultOption = document.createElement('option');
            defaultOption.value = "";
            defaultOption.textContent = "Selecciona una especialidad (Opcional)";
            selectEspecialidad.appendChild(defaultOption);

            // If a career is selected and has defined specialties
            if (carreraSeleccionada && especialidadesPorCarrera[carreraSeleccionada]) {
                especialidadesPorCarrera[carreraSeleccionada].forEach(especialidad => {
                    const option = document.createElement('option');
                    // Create a value for the backend, typically slug-like (lowercase, hyphens instead of spaces, no accents)
                    const slug = especialidad.toLowerCase().replace(/\s/g, '-').normalize("NFD").replace(/[\u0300-\u036f]/g, ""); 
                    option.value = slug;
                    option.textContent = especialidad;

                    // Set 'selected' attribute if this option matches the initial value or old input
                    // Ensure the slug is compared to the initialSpecialtyValue (which is also a slug)
                    if (slug === initialSpecialtyValue) {
                        option.selected = true;
                    }
                    selectEspecialidad.appendChild(option);
                });
            }
        }

        // Listen for the 'change' event on the career select
        selectCarrera.addEventListener('change', actualizarEspecialidades);

        // Set the initial career selection programmatically
        selectCarrera.value = initialCareerValue; 
        
        // Execute the function once on page load to initialize the specialty dropdown with correct options and selection
        actualizarEspecialidades(); 
    </script>
</x-layout>