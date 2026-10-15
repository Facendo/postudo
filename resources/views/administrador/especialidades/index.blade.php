<x-layout title='PostUDO || Especialidades'>

    <div class="page-content"
        style="width: 90%; max-width: 1200px; margin: 40px auto; display: flex; flex-direction: column; align-items: center;">
        {{-- Título de la sección --}}
        <div class="content_texto_bienvenida">
            <label>Listado de Especialidades</label>
        </div>

        <div class="action-buttons-container">
            <a href="{{ route('administrador.creacion.index') }}" class="button_body">
                <i class="fa-solid fa-plus icon-left"></i> Nueva Especialidad
            </a>
        </div>

        {{-- Tabla para mostrar las especialidades --}}
        <table class="table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Carrera Asociada</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($especialidades as $especialidad)
                    <tr>
                        <td>{{ $especialidad->codigo_especialidad }}</td>
                        <td>{{ $especialidad->nombre }}</td>
                        <td>{{ $especialidad->carrera ? $especialidad->carrera->nombre : 'Sin Carrera' }}</td>

                        {{-- Columna de acciones con botón para Eliminar --}}
                        <td class="table-actions">
                            <form
                                action="{{ route('administrador.especialidad.destroy', $especialidad->codigo_especialidad) }}"
                                method="POST" class="inline-form"
                                onsubmit="return confirm('¿Está seguro de eliminar esta especialidad?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-table-action" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 20px;">No hay especialidades registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layout>