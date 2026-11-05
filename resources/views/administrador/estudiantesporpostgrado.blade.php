<x-layout title='PostUDO || Estudiantes por Postgrado'>

    <div
        style="width: 90%; max-width: 1100px; margin: 40px auto; display: flex; flex-direction: column; align-items: center; gap: 30px;">

        <div class="content_texto_bienvenida" style="padding: 20px 30px;">
            <label style="font-size: 2rem; margin-bottom: 0;">Filtrar Estudiantes por Postgrado</label>
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
            <form action="{{ route('administrador.estudiantes_por_postgrado') }}" method="GET">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="postgrado" class="form-label">Seleccione un Postgrado:</label>
                    <select name="postgrado" id="postgrado" class="form-input" onchange="this.form.submit()">
                        <option value="">-- Seleccione un postgrado --</option>
                        @foreach ($postgrados as $postgrado)
                            <option value="{{ $postgrado->id_postgrado }}" {{ $selectedPostgrado == $postgrado->id_postgrado ? 'selected' : '' }}>
                                {{ $postgrado->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        {{-- Resultados del postgrado seleccionado --}}
        @if ($selectedPostgrado)
            <div style="width: 100%;">
                <h3 style="color: white; margin-bottom: 20px; text-align: center;">
                    Estudiantes del postgrado: <strong>{{ $selectedPostgradoNombre }}</strong>
                </h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Correo</th>
                            <th>Mención</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($estudiantes as $estudiante)
                            <tr>
                                <td>{{ $estudiante->cedula }}</td>
                                <td>{{ $estudiante->nombre }}</td>
                                <td>{{ $estudiante->apellido }}</td>
                                <td>{{ $estudiante->correo }}</td>
                                <td>{{ $estudiante->mencion?->nombre ?? $estudiante->especialidad }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 20px;">No hay estudiantes asociados a este
                                    postgrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Estudiantes sin mención (por lo tanto, sin postgrado) asignado --}}
        <div style="width: 100%;">
            <h3 style="color: white; margin-bottom: 20px; text-align: center;">
                Sin postgrado asignado
                <span style="font-size: 0.9rem; font-weight: normal; opacity: 0.75;">
                    (estudiantes que aún no tienen mención)
                </span>
            </h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Correo</th>
                        <th>Carrera (registro antiguo)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sinPostgrado as $estudiante)
                        <tr>
                            <td>{{ $estudiante->cedula }}</td>
                            <td>{{ $estudiante->nombre }}</td>
                            <td>{{ $estudiante->apellido }}</td>
                            <td>{{ $estudiante->correo }}</td>
                            <td>{{ $estudiante->carrera }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px;">Todos los estudiantes tienen un
                                postgrado asignado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layout>
