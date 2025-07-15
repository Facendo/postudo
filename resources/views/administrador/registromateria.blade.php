<x-layout title='PostUDO || Registro de Materia'>
    <section class="login-page-container">
        <div class="registration-form">
            <h2 class="registration-form-title">Registro de Materia</h2>

            {{-- Form for subject registration --}}
            <form action="{{ route('administrador.gestionmaterias.store') }}" method="POST">
                @csrf
                
                {{-- Código de Materia --}}
                <div class="form-group">
                    <label for="codigo_materia" class="form-label">Código de Materia</label>
                    <input type="number" id="codigo_materia" name="codigo_materia" class="form-input" placeholder="Ej: 101" required>
                </div>

                {{-- Nombre de la Materia --}}
                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre de la Materia</label>
                    <input type="text" id="nombre" name="nombre" class="form-input" placeholder="Ej: Programación Avanzada" required>
                </div>

                {{-- Carrera Selection --}}
                <div class="form-group">
                    <label for="carrera" class="form-label">Carrera</label>
                    <select id="carrera" name="carrera" class="form-select" required>
                        <option value="">Selecciona la Carrera</option>
                        {{-- Directly hardcoding careers for simplicity, matching JS keys --}}
                        {{-- In a real app, you'd fetch these from the database (e.g., App\Models\Carrera::all()) --}}
                        {{-- and ensure their 'value' attributes match the keys in especialidadesPorCarrera --}}
                        <option value="informatica">Ingeniería Informática</option>
                        <option value="civil">Ingeniería Civil</option>
                        <option value="electrica">Ingeniería Eléctrica</option>
                        <option value="mecanica">Ingeniería Mecánica</option>
                        <option value="quimica">Ingeniería Química</option>
                        <option value="administracion">Administración</option>
                        <option value="contaduria">Contaduría Pública</option>
                        <option value="derecho">Derecho</option>
                        {{-- If you fetch from DB like last time, ensure values match JS keys: --}}
                        {{-- @foreach(App\Models\Carrera::all() as $carrera) --}}
                        {{--     <option value="{{ strtolower($carrera->codigo_carrera) }}">{{ $carrera->nombre_carrera }}</option> --}}
                        {{-- @endforeach --}}
                    </select>
                </div>

                {{-- Especialidad Selection (Dynamically populated by JS) --}}
                <div class="form-group">
                    <label for="especialidad" class="form-label">Especialidad</label>
                    <select id="especialidad" name="codigo_especialidad" class="form-select" required>
                        <option value="">Selecciona una carrera primero</option>
                    </select>
                </div>

                {{-- Número de Sección --}}
                <div class="form-group">
                    <label for="nro_seccion" class="form-label">Número de Sección</label>
                    <input type="number" id="nro_seccion" name="nro_seccion" class="form-input" placeholder="Ej: 1" required>
                </div>

                {{-- Prelación (Optional) --}}
                <div class="form-group">
                    <label for="prelacion">Prelación</label>
                    <input type="number" class="form-control" id="prelacion" name="prelacion" required>
                </div>
                
                {{-- Submit Button --}}
                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Registrar Materia</button>
                </div>

            </form>
        </div>
    </section>

    ---

    <script>
        // Define las especialidades por carrera.
        // **IMPORTANT:** The keys here (e.g., 'informatica') MUST EXACTLY match the 'value' attributes
        // of your career options in the <select id="carrera"> dropdown.
        const especialidadesPorCarrera = {
            informatica: [
                "Desarrollo de Software",
                "Redes y Telecomunicaciones",
                "Ciberseguridad",
                "Inteligencia Artificial",
                "Base de Datos"
            ],
            civil: [
                "Estructuras",
                "Hidráulica",
                "Vías y Transportes",
                "Construcción",
                "Ingeniería Sanitaria"
            ],
            electrica: [
                "Sistemas de Potencia",
                "Control y Automatización",
                "Máquinas Eléctricas",
                "Electrónica de Potencia"
            ],
            mecanica: [
                "Diseño Mecánico",
                "Termodinámica y Fluidos",
                "Automatización Industrial",
                "Manufactura"
            ],
            quimica: [
                "Procesos Industriales",
                "Bioingeniería",
                "Petroquímica",
                "Ingeniería Ambiental"
            ],
            administracion: [
                "Gerencia Empresarial",
                "Recursos Humanos",
                "Finanzas",
                "Mercadeo",
                "Operaciones"
            ],
            contaduria: [
                "Auditoría",
                "Tributaria",
                "Costos",
                "Finanzas Corporativas"
            ],
            derecho: [
                "Derecho Penal",
                "Derecho Civil",
                "Derecho Laboral",
                "Derecho Mercantil",
                "Derecho Internacional"
            ]
            // Add more careers and their specialties here if needed
        };

        // Get references to the select elements
        const selectCarrera = document.getElementById('carrera');
        const selectEspecialidad = document.getElementById('especialidad'); // Ensure this ID matches your HTML

        // Function to update specialty options
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
                    option.value = especialidad.toLowerCase().replace(/\s/g, '-').normalize("NFD").replace(/[\u0300-\u036f]/g, ""); 
                    option.textContent = especialidad;
                    selectEspecialidad.appendChild(option);
                });
            }
        }

        // Listen for the 'change' event on the career select
        selectCarrera.addEventListener('change', actualizarEspecialidades);

        // Execute the function once on page load to initialize the specialty dropdown
        actualizarEspecialidades();
    </script>
</x-layout>