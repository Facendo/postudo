<x-layout title="Pagos Actualizados">

    <div class="content_texto_bienvenida">
        <label>Pagos Actualizados</label>
    </div>
    <div class="action-buttons-container">
        <div>
            <table>
                <thead>
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Especialidad</th>
                        <th>Banco Emisor</th>
                        <th>Banco Receptor</th>
                        <th>Referencia</th>
                        <th>Monto (Bs.)</th>
                        <th>Asunto</th>
                        <th>Fecha de Registro</th>
                        <th>Estado de Pago</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pagos as $pago)
                        <tr>
                            <td>{{ $pago->cedula }}</td>
                            <td>{{ $pago->nombre }}</td>
                            <td>{{ $pago->estudiante->especialidadRel->nombre ?? $pago->estudiante->especialidad ?? 'N/A' }}
                            </td>
                            <td>{{ $pago->banco_emisor }}</td>
                            <td>{{ $pago->banco_receptor }}</td>
                            <td>{{ $pago->referencia }}</td>
                            <td>{{ number_format($pago->monto, 2, ',', '.') }}</td>
                            <td>{{ $pago->asunto }}</td>
                            <td>{{ $pago->created_at->format('d/m/Y H:i') }}</td>
                            <td><span class="badge badge-success">{{ $pago->estado }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 2rem; opacity: 0.7;">No hay pagos
                                actualizados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

</x-layout>