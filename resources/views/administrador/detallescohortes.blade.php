<x-layout title='PostUDO || Cohorte'>
    <div class="page-content" style="width: 90%; max-width: 900px; margin: 40px auto; display: flex; flex-direction: column; align-items: center;">
        {{-- Título de la sección --}}
        <div class="content_texto_bienvenida">
            <label>Detalles de la {{ $cohorte->nro_de_cohorte }}° cohorte</label>
        </div>

        {{-- Datos de la cohorte --}}
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nro. de Cohorte</th>
                    <th>Postgrado</th>
                    <th>Mención</th>
                    <th>Fecha de Inicio</th>
                    <th>Fecha de Fin</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $cohorte->codigo_cohorte }}</td>
                    <td>{{ $cohorte->nro_de_cohorte }}</td>
                    <td>{{ $cohorte->postgrado?->nombre ?? '—' }}</td>
                    <td>{{ $cohorte->mencion?->nombre ?? '—' }}</td>
                    <td>{{ $cohorte->fecha_inicio }}</td>
                    <td>{{ $cohorte->fecha_fin }}</td>
                </tr>
            </tbody>
        </table>

        <div class="action-buttons-container">
            <a href="{{ route('administrador.gestioncohorte') }}" class="button_body secondary-button">
                <i class="fa-solid fa-arrow-left icon-left"></i> Volver a Cohortes
            </a>
        </div>
    </div>
</x-layout>
