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
                </tr>
            </thead>
            <tbody>
                @forelse ($coordinadores as $coordinador)
                    <tr>
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
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px;">
                            No hay coordinadores de postgrado registrados. Crea uno desde «Nuevo Coordinador».
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layout>
