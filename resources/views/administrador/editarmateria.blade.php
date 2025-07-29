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