<x-layout title='PostUDO || Estudiantes por Especialidad'>

    <div
        style="width: 90%; max-width: 1100px; margin: 40px auto; display: flex; flex-direction: column; align-items: center; gap: 30px;">

        <div class="content_texto_bienvenida" style="padding: 20px 30px;">
            <label style="font-size: 2rem; margin-bottom: 0;">Filtrar Estudiantes por Especialidad</label>
        </div>

        {{-- Cuadro glass del filtro --}}
        <div style="
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 35px 45px;
            box-shadow: 0 15px 40px rgba(255, 255, 255, 0.1);
            width: 100%;
            max-width: 550px;
        ">
            <form action="{{ route('administrador.prueba_especialidades') }}" method="GET">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="especialidad" class="form-label">Seleccione una Especialidad:</label>
                    <select name="especialidad" id="especialidad" class="form-input" onchange="this.form.submit()">
                        <option value="">-- Seleccione una especialidad --</option>
                        @foreach ($especialidades as $esp)
                            <option value="{{ $esp->codigo_especialidad }}" {{ $selectedEspecialidad == $esp->codigo_especialidad ? 'selected' : '' }}>
                                {{ $esp->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        {{-- Tabla de resultados --}}
        @if ($selectedEspecialidad)
            <div style="width: 100%;">
                <h3 style="color: white; margin-bottom: 20px; text-align: center;">
                    Mostrando inscritos en: <strong>{{ $selectedEspecialidadNombre }}</strong>
                </h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Correo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($estudiantes as $estudiante)
                            <tr>
                                <td>{{ $estudiante->cedula }}</td>
                                <td>{{ $estudiante->nombre }}</td>
                                <td>{{ $estudiante->apellido }}</td>
                                <td>{{ $estudiante->correo }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 20px;">No hay estudiantes asociados a esta
                                    especialidad.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</x-layout>