<x-layout title='PostUDO || Postgrado'>
    <section class="main-content-section page-content">
        {{-- Título de la sección --}}
        <div class="content_texto_bienvenida">
            <label>Detalles del Postgrado: {{ $postgrado->nombre }}</label>
        </div>

        <p style="color: white; opacity: 0.8; text-align: center; margin-bottom: 10px;">
            Área: <strong>{{ $postgrado->area->nombre ?? 'Sin área' }}</strong> &nbsp;|&nbsp;
            Duración: <strong>{{ $postgrado->duracion }}</strong> &nbsp;|&nbsp;
            Cohortes: <strong>{{ $postgrado->nro_cohortes }}</strong>
        </p>

        {{-- Botón para crear un nuevo cohorte --}}
        <div class="action-buttons-container">
            <a href="{{ route('administrador.gestioncohorte.create', $postgrado->id_postgrado) }}" class="button_body">
                <i class="fa-solid fa-plus icon-left"></i> Nuevo Cohorte
            </a>
        </div>

        {{-- MENCIONES DEL POSTGRADO --}}
        <h3 style="color: white; margin: 25px 0 12px;">Menciones</h3>

        @if ($errors->has('nombre'))
            <p class="error-message" style="margin-bottom: 12px;">{{ $errors->first('nombre') }}</p>
        @endif

        <table>
            <thead>
                <tr>
                    <th>Nombre de la Mención</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($postgrado->menciones as $mencion)
                    <tr>
                        <td>
                            {{-- Cada mención se renombra con su propio formulario --}}
                            <form action="{{ route('administrador.mencion.update', $mencion) }}" method="POST" class="inline-form" style="display: flex; gap: 8px;">
                                @csrf
                                @method('PUT')
                                <input type="text" name="nombre" class="form-input" value="{{ old('nombre', $mencion->nombre) }}" required style="max-width: 320px;">
                                <button type="submit" class="button_body" title="Guardar nombre">
                                    <i class="fas fa-save"></i>
                                </button>
                            </form>
                        </td>
                        <td class="table-actions">
                            <form action="{{ route('administrador.mencion.destroy', $mencion) }}" method="POST" class="inline-form"
                                onsubmit="return confirm('¿Eliminar la mención \"{{ $mencion->nombre }}\"? Los estudiantes que la tengan asignada quedarán sin mención.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button_body" title="Eliminar mención">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" style="text-align: center; padding: 20px;">Este postgrado aún no tiene menciones registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Registrar una nueva mención en este postgrado --}}
        <form action="{{ route('administrador.mencion.store', $postgrado) }}" method="POST" class="registration-form" style="display: flex; gap: 10px; align-items: flex-end; margin-top: 15px; flex-wrap: wrap;">
            @csrf
            <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 220px;">
                <label for="nombre_mencion_nueva" class="form-label">Nueva mención</label>
                <input type="text" id="nombre_mencion_nueva" name="nombre" class="form-input" placeholder="Ej: Finanzas" required>
            </div>
            <button type="submit" class="submit-button">Registrar Mención</button>
        </form>

        {{-- COHORTES DEL POSTGRADO --}}
        <h3 style="color: white; margin: 30px 0 12px;">Cohortes</h3>
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Postgrado</th>
                    <th>Fechas</th>
                    <th>Nro. de Cohorte</th>
                    <th>Ver Materias</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cohortes as $cohorte)
                    <tr>
                        <td>{{ $cohorte->codigo_cohorte }}</td>
                        <td>{{ $cohorte->codigo_postgrado }}</td>
                        <td>{{ $cohorte->fecha_inicio }} - {{ $cohorte->fecha_fin }}</td>
                        <td>{{ $cohorte->nro_de_cohorte }}</td>
                        <td>
                            <a href="{{ route('administrador.gestioncohorte.showdetalles', $cohorte->codigo_cohorte) }}" class="button_body" title="Ver Detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>

                        {{-- Columna de acciones con botones para Editar y Eliminar --}}
                        <td class="table-actions">
                            <a href="{{ route('administrador.gestioncohorte.edit', $cohorte->codigo_cohorte) }}" class="button_body" title="Editar">
                                <i class="fas fa-pencil-alt"></i>
                            </a>

                            <form action="{{ route('administrador.gestioncohorte.destroy', $cohorte->codigo_cohorte) }}" method="POST" class="inline-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button_body" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px;">No hay cohortes registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
</x-layout>
