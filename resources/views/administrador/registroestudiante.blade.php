<x-layout title='PostUDO || Registro de Estudiante'>

    {{-- Sección principal para el registro de estudiantes --}}
    <section class="login-page-container">
        <div class="registration-form">
            <h2 class="registration-form-title">Registro de Estudiante</h2>

            @if ($errors->any())
                <div class="form-group" style="border: 1px solid #e05757; background: rgba(224, 87, 87, 0.15); border-radius: 8px; padding: 12px 16px;">
                    <strong>Revisa los siguientes campos:</strong>
                    <ul style="margin: 8px 0 0 18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('estudiante.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="cedula" class="form-label">Cédula</label>
                    <input type="text" id="cedula" name="cedula" class="form-input" placeholder="Ej: V-12345678"
                        value="{{ old('cedula') }}" required>
                </div>

                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-input" placeholder="Tu nombre" value="{{ old('nombre') }}" required>
                </div>

                <div class="form-group">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" id="apellido" name="apellido" class="form-input" placeholder="Tu apellido"
                        value="{{ old('apellido') }}" required>
                </div>

                <div class="form-group">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="email" id="correo" name="correo" class="form-input" placeholder="ejemplo@correo.com"
                        value="{{ old('correo') }}" required>
                </div>

                <div class="form-group">
                    <label for="edad" class="form-label">Edad</label>
                    <input type="number" id="edad" name="edad" class="form-input" placeholder="Ej: 20" min="15" max="99"
                        value="{{ old('edad') }}" required>
                </div>

                {{-- Cascade Área -> Postgrado -> Mención --}}
                <div class="form-group">
                    <label for="area" class="form-label">Área</label>
                    <select id="area" class="form-select" required>
                        <option value="">Selecciona un Área</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->codigo }}">{{ $area->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="postgrado" class="form-label">Postgrado</label>
                    <select id="postgrado" class="form-select" required>
                        <option value="">Selecciona primero un Área</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="codigo_mencion" class="form-label">Mención</label>
                    <select id="codigo_mencion" name="codigo_mencion" class="form-select" required>
                        <option value="">Selecciona primero un Postgrado</option>
                    </select>
                    @error('codigo_mencion')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Registrar Estudiante</button>
                </div>

            </form>
        </div>
    </section>

    <script>
        const areasData = @json($areas);
        const postgradosData = @json($postgrados);
        const mencionesData = @json($menciones);

        const areaSelect = document.getElementById('area');
        const postgradoSelect = document.getElementById('postgrado');
        const mencionSelect = document.getElementById('codigo_mencion');

        function poblarPostgrados(codigoArea, seleccionado = null) {
            postgradoSelect.innerHTML = '';
            mencionSelect.innerHTML = '<option value="">Selecciona primero un Postgrado</option>';

            if (!codigoArea) {
                postgradoSelect.innerHTML = '<option value="">Selecciona primero un Área</option>';
                return;
            }

            const filtrados = postgradosData.filter(p => String(p.codigo_area) === String(codigoArea));
            if (!filtrados.length) {
                postgradoSelect.innerHTML = '<option value="">No hay postgrados en esta área</option>';
                return;
            }

            postgradoSelect.append(new Option('Selecciona un Postgrado', ''));
            filtrados.forEach(p => postgradoSelect.append(new Option(p.nombre, p.id_postgrado)));

            if (seleccionado) postgradoSelect.value = seleccionado;
        }

        function poblarMenciones(idPostgrado, seleccionada = null) {
            mencionSelect.innerHTML = '';

            if (!idPostgrado) {
                mencionSelect.innerHTML = '<option value="">Selecciona primero un Postgrado</option>';
                return;
            }

            const filtradas = mencionesData.filter(m => String(m.id_postgrado) === String(idPostgrado));
            if (!filtradas.length) {
                mencionSelect.innerHTML = '<option value="">Este postgrado no tiene menciones registradas</option>';
                return;
            }

            mencionSelect.append(new Option('Selecciona una Mención', ''));
            filtradas.forEach(m => mencionSelect.append(new Option(m.nombre, m.id_mencion)));

            if (seleccionada) mencionSelect.value = seleccionada;
        }

        areaSelect.addEventListener('change', function () {
            poblarPostgrados(areaSelect.value);
        });

        postgradoSelect.addEventListener('change', function () {
            poblarMenciones(postgradoSelect.value);
        });

        // Inicialización: conserva la selección previa tras un error de validación
        const mencionAnterior = @json(old('codigo_mencion'));
        if (mencionAnterior) {
            const mencion = mencionesData.find(m => String(m.id_mencion) === String(mencionAnterior));
            if (mencion && mencion.postgrado) {
                areaSelect.value = mencion.postgrado.codigo_area;
                poblarPostgrados(mencion.postgrado.codigo_area, mencion.id_postgrado);
                poblarMenciones(mencion.id_postgrado, mencion.id_mencion);
            }
        }
    </script>

</x-layout>
