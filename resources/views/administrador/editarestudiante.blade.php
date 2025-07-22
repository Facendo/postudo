<x-layout title='PostUDO || Editar Estudiante'>
    <section class="login-page-container">
        <div class="registration-form">
            <h2 class="registration-form-title">Editar Estudiante</h2>

            <form action="{{ route('administrador.gestion_estudiante.update', $estudiante) }}" method="POST">
                @csrf
                @method('PUT') {{-- This blade directive spoofs the PUT method for HTML forms --}}

                <div class="form-group">
                    <label for="cedula" class="form-label">Cédula</label>
                    {{-- Populate the input with the existing student data --}}
                    <input type="text" id="cedula" name="cedula" class="form-input" placeholder="Ej: V-12345678" value="{{ old('cedula', $estudiante->cedula) }}" required>
                </div>

                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-input" placeholder="Tu nombre" value="{{ old('nombre', $estudiante->nombre) }}" required>
                </div>

                <div class="form-group">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" id="apellido" name="apellido" class="form-input" placeholder="Tu apellido" value="{{ old('apellido', $estudiante->apellido) }}" required>
                </div>

                <div class="form-group">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="email" id="correo" name="correo" class="form-input" placeholder="ejemplo@correo.com" value="{{ old('correo', $estudiante->correo) }}" required>
                </div>

                <div class="form-group">
                    <label for="edad" class="form-label">Edad</label>
                    <input type="number" id="edad" name="edad" class="form-input" placeholder="Ej: 20" min="15" max="99" value="{{ old('edad', $estudiante->edad) }}" required>
                </div>

                <div class="form-group">
                    <label for="carrera" class="form-label">Carrera</label>
                    <select id="carrera" name="carrera" class="form-select" required>
                        <option value="">Selecciona tu carrera</option>
                        @foreach ($carreras as $carrera)
                            <option value="{{ $carrera->id }}" {{ old('carrera', $estudiante->carrera_id) == $carrera->id ? 'selected' : '' }}>
                                {{ $carrera->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="especialidad" class="form-label">Especialidad</label>
                    <select id="especialidad" name="especialidad" class="form-select">
                        <option value="">Selecciona una especialidad (Opcional)</option>
                        {{-- Options will be populated by JavaScript --}}
                    </select>
                </div>

                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Actualizar Estudiante</button>
                </div>
            </form>
        </div>
    </section>

    <script>
        // Define las especialidades por carrera
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
            // Añade más carreras y sus especialidades aquí
        };

        // Obtener las referencias a los elementos select
        const selectCarrera = document.getElementById('carrera');
        const selectEspecialidad = document.getElementById('especialidad');

        // Capture the original student's career and specialty
        const currentCareer = "{{ old('carrera', $estudiante->carrera) }}";
        const currentEspecialidad = "{{ old('especialidad', $estudiante->especialidad) }}";


        // Función para actualizar las opciones de especialidad
        function actualizarEspecialidades() {
            const carreraSeleccionada = selectCarrera.value;
            selectEspecialidad.innerHTML = ''; // Limpiar opciones actuales

            // Añadir la opción por defecto
            const defaultOption = document.createElement('option');
            defaultOption.value = "";
            defaultOption.textContent = "Selecciona una especialidad (Opcional)";
            selectEspecialidad.appendChild(defaultOption);

            // Si se ha seleccionado una carrera y tiene especialidades definidas
            if (carreraSeleccionada && especialidadesPorCarrera[carreraSeleccionada]) {
                especialidadesPorCarrera[carreraSeleccionada].forEach(especialidad => {
                    const option = document.createElement('option');
                    // Ensure the value matches the format stored in your database if applicable
                    const formattedEspecialidadValue = especialidad.toLowerCase().replace(/\s/g, '-');
                    option.value = formattedEspecialidadValue;
                    option.textContent = especialidad;

                    // If this specialty matches the current student's specialty, mark it as selected
                    if (formattedEspecialidadValue === currentEspecialidad) {
                        option.selected = true;
                    }
                    selectEspecialidad.appendChild(option);
                });
            }
        }

        // Escuchar el evento 'change' en el select de carrera
        selectCarrera.addEventListener('change', actualizarEspecialidades);

        // Ejecutar la función una vez al cargar la página para inicializar
        // This will populate specialties based on the pre-selected career
        actualizarEspecialidades();
    </script>
</x-layout>