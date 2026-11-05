<x-layout title='PostUDO || Gestión de Coordinadores de Postgrado'>
    <div class="page-content" style="width: 90%; max-width: 1200px; margin: 40px auto; display: flex; flex-direction: column; align-items: center;">
        <div class="content_texto_bienvenida">
            <label>Listado de Coordinadores de Postgrado</label>
        </div>

        <div class="action-buttons-container">
            <a href="{{ route('coordinador.coordinador_postgrado.create') }}" class="button_body">
                <i class="fa-solid fa-plus icon-left"></i> Nuevo Coordinador
            </a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Cédula</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Correo</th>
                    <th>Postgrados Asignados</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($coordinadores as $coordinador)
                    <tr data-cedula="{{ $coordinador->cedula }}"
                        data-nombre="{{ $coordinador->nombre }} {{ $coordinador->apellido }}"
                        data-postgrados="{{ $coordinador->postgrados->pluck('nombre')->join(', ') }}">
                        <td>{{ $coordinador->cedula }}</td>
                        <td>{{ $coordinador->nombre }}</td>
                        <td>{{ $coordinador->apellido }}</td>
                        <td>{{ $coordinador->correo }}</td>
                        <td>
                            {{-- Un postgrado solo tiene UN coordinador; uno puede tener varios --}}
                            @forelse ($coordinador->postgrados as $postgrado)
                                {{ $postgrado->nombre }} ({{ $postgrado->id_postgrado }}){{ $loop->last ? '' : ', ' }}
                            @empty
                                —
                            @endforelse
                        </td>

                        {{-- Solo el coordinador general ve esta página y puede borrar --}}
                        <td class="table-actions">
                            <form action="{{ route('coordinador.coordinador_postgrado.destroy', $coordinador) }}" method="POST" class="inline-form"
                                data-eliminar-coordinador data-cedula="{{ $coordinador->cedula }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-table-action" title="Eliminar coordinador">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px;">
                            No hay coordinadores de postgrado registrados. Crea uno desde «Nuevo Coordinador».
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        // Confirmación previa al borrado: muestra los postgrados que quedarán
        // «Sin coordinador» para asignárselos después a otra persona.
        document.querySelectorAll('form[data-eliminar-coordinador]').forEach(form => {
            form.addEventListener('submit', evento => {
                if (form.dataset.confirmado === '1') return;

                evento.preventDefault();

                const fila = form.closest('tr');
                let mensaje = `¿Eliminar al coordinador "${fila.dataset.nombre}" (cédula ${fila.dataset.cedula})?`;

                if (fila.dataset.postgrados) {
                    mensaje += `\n\nQuedarán SIN coordinador: ${fila.dataset.postgrados}.`;
                }

                mensaje += '\n\nSi ya creó cuenta en el sistema, también se eliminará su usuario y quedará sin acceso.';

                if (confirm(mensaje)) {
                    form.dataset.confirmado = '1';
                    form.submit();
                }
            });
        });
    </script>
</x-layout>
