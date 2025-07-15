<x-layout title='PostUDO || Inicio'>
        <section class="login-page-container">
            <div class="registration-form">
                <h2 class="registration-form-title">Registro de Estudiante</h2>

                <form action="{{ route('estudiante.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="cedula" class="form-label">Cédula</label>
                        <input type="text" id="cedula" name="cedula" class="form-input" placeholder="Ej: V-12345678" required>
                    </div>

                    <div class="form-group">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-input" placeholder="Tu nombre" required>
                    </div>

                    <div class="form-group">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" id="apellido" name="apellido" class="form-input" placeholder="Tu apellido" required>
                    </div>

                    <div class="form-group">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input type="email" id="correo" name="correo" class="form-input" placeholder="ejemplo@correo.com" required>
                    </div>

                    <div class="form-group">
                        <label for="edad" class="form-label">Edad</label>
                        <input type="number" id="edad" name="edad" class="form-input" placeholder="Ej: 20" min="15" max="99" required>
                    </div>

                    <div class="form-group">
                        <label for="carrera" class="form-label">Carrera</label>
                        <select id="carrera" name="carrera" class="form-select" required>
                            <option value="">Selecciona tu carrera</option>
                            <option value="informatica">Ingeniería Informática</option>
                            <option value="civil">Ingeniería Civil</option>
                            <option value="electrica">Ingeniería Eléctrica</option>
                            <option value="mecanica">Ingeniería Mecánica</option>
                            <option value="quimica">Ingeniería Química</option>
                            <option value="administracion">Administración</option>
                            <option value="contaduria">Contaduría Pública</option>
                            <option value="derecho">Derecho</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="especialidad" class="form-label">Especialidad</label>
                        <select id="especialidad" name="especialidad" class="form-select">
                            <option value="">Selecciona una carrera primero</option>
                        </select>
                    </div>
                    

                    <div class="form-actions" style="justify-content: center;">
                        <button type="submit" class="submit-button">Registrar Estudiante</button>
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
                    option.value = especialidad.toLowerCase().replace(/\s/g, '-'); // Valor para el backend (ej: "desarrollo-software")
                    option.textContent = especialidad;
                    selectEspecialidad.appendChild(option);
                });
            }
        }

        // Escuchar el evento 'change' en el select de carrera
        selectCarrera.addEventListener('change', actualizarEspecialidades);

        // Ejecutar la función una vez al cargar la página para inicializar
        actualizarEspecialidades();
    </script>


</x-layout>

