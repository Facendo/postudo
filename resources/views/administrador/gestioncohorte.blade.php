<x-layout title='PostUDO || Gestión de Cohortes'>
    <div class="page-content" style="width: 90%; max-width: 1200px; margin: 40px auto; display: flex; flex-direction: column; align-items: center;">
        <div class="content_texto_bienvenida">
            <label>Listado de Cohortes</label>
        </div>

        {{-- Apertura de cohortes: cupos del coordinador de postgrado
             (admin y coordinador general crean libre, no ven nada aquí) --}}
        @if (Auth::user()->rol === 'coordinador_postgrado')
            <p style="margin: 0 0 8px; padding: 6px 16px; border: 1px solid var(--borde); background: var(--superficie); border-radius: 999px; color: var(--texto);">
                Cupos de apertura disponibles: <strong>{{ $cuposDisponibles }}</strong>
            </p>
            @if ($cuposDisponibles < 1)
                <p style="margin: 0 0 14px; padding: 10px 16px; border: 1px solid rgba(255, 193, 7, 0.55); background: rgba(255, 193, 7, 0.12); border-radius: 8px; color: #b45309; text-align: center;">
                    No puedes crear cohortes: solicita la apertura de cupos al coordinador general
                    desde «Apertura de Cohortes» en tu panel.
                </p>
            @endif
        @endif

        <div class="action-buttons-container">
            {{-- create no exige postgrado: el formulario pide elegir la mención --}}
            @if (Auth::user()->rol === 'coordinador_postgrado' && $cuposDisponibles < 1)
                <span class="button_body" style="opacity: 0.55; cursor: not-allowed;" title="Sin cupos de apertura: solicítalos al coordinador general">
                    <i class="fa-solid fa-plus icon-left"></i> Nueva Cohorte
                </span>
            @else
                <a href="{{ route('administrador.gestioncohorte.create') }}" class="button_body">
                    <i class="fa-solid fa-plus icon-left"></i> Nueva Cohorte
                </a>
            @endif
        </div>

        <table>
            <thead>
                <tr>
                    <th>Código de Cohorte</th>
                    <th>Postgrado</th>
                    <th>Mención</th>
                    <th>Fecha de Inicio</th>
                    <th>Fecha de Fin</th>
                    <th>Número de Cohorte</th>
                    <th>Preinscripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cohortes as $cohorte)
                    <tr>
                        <td>{{ $cohorte->codigo_cohorte }}</td>
                        <td>{{ $cohorte->postgrado?->nombre ?? $cohorte->codigo_postgrado }}</td>
                        <td>{{ $cohorte->mencion?->nombre ?? '—' }}</td>
                        <td>{{ \Carbon\Carbon::parse($cohorte->fecha_inicio)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($cohorte->fecha_fin)->format('d/m/Y') }}</td>
                        <td>{{ $cohorte->nro_de_cohorte }}</td>
                        <td>{{ $cohorte->ventanaPreinscripcion() ?? '—' }}</td>
                        <td class="table-actions">
                            {{-- Botón de Editar --}}
                            <a href="{{ route('administrador.gestioncohorte.edit', $cohorte) }}" class="btn-table-action" title="Editar">
                                <i class="fas fa-pencil-alt"></i>
                            </a>

                            {{-- Botón de Eliminar (usando un formulario para solicitudes DELETE) --}}
                            <form action="{{ route('administrador.gestioncohorte.destroy', $cohorte) }}" method="POST" class="inline-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-table-action" title="Eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar esta cohorte?');">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 20px;">
                            {{-- Mensaje según rol: al coordinador de postgrado sin
                                 asignación no se le invita a crear lo que no puede --}}
                            {{ Auth::user()->rol === 'coordinador_postgrado'
                                ? 'No tienes cohortes asignadas actualmente.'
                                : 'No hay cohortes registradas. Crea una desde «Nueva Cohorte».' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layout>
