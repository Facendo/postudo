<x-layout title="Pagos Pendientes">

    <div class="content_texto_bienvenida">
        <label>Pagos Pendientes</label>
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
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pagos as $pago)
                        <tr>
                            <td>{{ $pago->cedula }}</td>
                            <td>{{ $pago->nombre }}</td>
                            <td>{{ $pago->estudiante->especialidad ?? 'N/A' }}</td>
                            <td>{{ $pago->banco_emisor }}</td>
                            <td>{{ $pago->banco_receptor }}</td>
                            <td>{{ $pago->referencia }}</td>
                            <td>{{ number_format($pago->monto, 2, ',', '.') }}</td>
                            <td>{{ $pago->asunto }}</td>
                            <td>{{ $pago->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $pago->estado }}</td>
                            <td>
                                <form action="{{ route('pago.actualizar', $pago->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="button_body">Actualizar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="text-align: center; padding: 2rem; opacity: 0.7;">No hay pagos
                                pendientes.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

</x-layout>