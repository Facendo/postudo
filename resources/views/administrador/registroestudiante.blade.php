<x-layout title='PostUDO || Registro de Estudiante'>

    {{-- Sección principal para el registro de estudiantes --}}
    <section class="login-page-container">
        <div class="registration-form">
            <h2 class="registration-form-title">Registro de Estudiante</h2>
            <form action="{{ route('estudiante.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="cedula" class="form-label">Cédula</label>
                    <input type="text" id="cedula" name="cedula" class="form-input" placeholder="Ej: V-12345678"
                        required>
                </div>

                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-input" placeholder="Tu nombre" required>
                </div>

                <div class="form-group">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" id="apellido" name="apellido" class="form-input" placeholder="Tu apellido"
                        required>
                </div>

                <div class="form-group">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="email" id="correo" name="correo" class="form-input" placeholder="ejemplo@correo.com"
                        required>
                </div>

                <div class="form-group">
                    <label for="edad" class="form-label">Edad</label>
                    <input type="number" id="edad" name="edad" class="form-input" placeholder="Ej: 20" min="15" max="99"
                        required>
                </div>

                <div class="form-group">
                    <label for="carrera" class="form-label">Carrera</label>
                    <select id="carrera" name="carrera" class="form-select" required>
                        <option value="">Selecciona tu carrera</option>
                        @empty($carreras)
                            <option value="">No hay carreras disponibles</option>
                        @else
                            @foreach ($carreras as $carrera)
                                <option value="{{ $carrera->nombre }}" data-id="{{ $carrera->id_carrera }}">
                                    {{ $carrera->nombre }}</option>
                            @endforeach
                        @endempty
                    </select>
                </div>

                <div class="form-group">
                    <label for="especialidad" class="form-label">Especialidad</label>
                    <select id="especialidad" name="especialidad" class="form-select" required>
                        <option value="">Selecciona primero una carrera</option>
                    </select>
                </div>


                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Registrar Estudiante</button>
                </div>

            </form>
        </div>
    </section>

    <script>
        // Datos de especialidades pasados desde el controlador
        const especialidades = @json($especialidades);

        const carreraSelect = document.getElementById('carrera');
        const especialidadSelect = document.getElementById('especialidad');

        carreraSelect.addEventListener('change', function () {
            const selectedOption = carreraSelect.options[carreraSelect.selectedIndex];
            const idCarrera = selectedOption.getAttribute('data-id');

            // Limpiar opciones anteriores
            especialidadSelect.innerHTML = '';

            if (!idCarrera) {
                especialidadSelect.innerHTML = '<option value="">Selecciona primero una carrera</option>';
                return;
            }

            // Filtrar especialidades por la carrera seleccionada
            const filtradas = especialidades.filter(e => e.id_carrera == idCarrera);

            if (filtradas.length === 0) {
                especialidadSelect.innerHTML = '<option value="">No hay especialidades para esta carrera</option>';
                return;
            }

            // Agregar opción por defecto
            const defaultOpt = document.createElement('option');
            defaultOpt.value = '';
            defaultOpt.textContent = 'Selecciona una especialidad';
            especialidadSelect.appendChild(defaultOpt);

            // Agregar especialidades filtradas con su código
            filtradas.forEach(esp => {
                const opt = document.createElement('option');
                opt.value = esp.codigo_especialidad;
                opt.textContent = esp.nombre + ' (Código: ' + esp.codigo_especialidad + ')';
                especialidadSelect.appendChild(opt);
            });
        });
    </script>

</x-layout>