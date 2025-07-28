<x-layout title='PostUDO || Gestión de Cohortes'>
    <section class="main-content-section page-content">
        <div class="content_texto_bienvenida">
            <label>Listado de Cohortes</label>
        </div>

        <div class="action-buttons-container">
            {{-- Asegúrate de que la ruta sea correcta para crear una nueva cohorte --}}
            {{-- Se asume que la ruta es 'administrador.gestioncohorte.create' --}}
            <a href="{{ route('administrador.gestioncohorte.create') }}" class="button_body">
                <i class="fa-solid fa-plus icon-left"></i> Nueva Cohorte
            </a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Código de Cohorte</th>
                    <th>Código de Postgrado</th>
                    <th>Fecha de Inicio</th>
                    <th>Fecha de Fin</th>
                    <th>Número de Cohorte</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cohortes as $cohorte)
                    <tr>
                        <td>{{ $cohorte->codigo_cohorte }}</td>
                        <td>{{ $cohorte->codigo_postgrado }}</td>
                        <td>{{ \Carbon\Carbon::parse($cohorte->fecha_inicio)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($cohorte->fecha_fin)->format('d/m/Y') }}</td>
                        <td>{{ $cohorte->nro_de_cohorte }}</td>
                        <td class="table-actions">
                            {{-- Botón de Editar --}}
                            <a href="{{ route('administrador.gestioncohorte.edit', $cohorte) }}" class="button_body" title="Editar">
                                <i class="fas fa-pencil-alt"></i>
                            </a>

                            {{-- Botón de Eliminar (usando un formulario para solicitudes DELETE) --}}
                            <form action="{{ route('administrador.gestioncohorte.destroy', $cohorte) }}" method="POST" class="inline-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button_body" title="Eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar esta cohorte?');">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px;">No hay cohortes registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
</x-layout>