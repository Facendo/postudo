<x-layout title="Control de Pago">
        <h1 class="titulo">Lista de Pagos</h1>
        <div class="action-buttons-container">
        <div>
            <table>
                <thead>
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre</th>
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
                    @foreach ($pagos as $pago)
                        <tr>
                            <td>{{ $pago->cedula }}</td>
                            <td>{{ $pago->nombre }}</td>
                            <td>{{ $pago->banco_emisor }}</td>
                            <td>{{ $pago->banco_receptor }}</td>
                            <td>{{ $pago->referencia }}</td>
                            <td>{{ number_format($pago->monto, 2, ',', '.') }}</td>
                            <td>{{ $pago->asunto }}</td>
                            <td>{{ $pago->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $pago->estado }}</td>
                            @if($pago->estado === 'Pendiente')
                                <td>
                                    <form action="{{ route('pago.actualizar', $pago->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="button_body">Actualizar</button>
                                    </form>
                                </td>
                            @endif
                            @if($pago->estado === 'Actualizado')
                                <td>
                                    <span class="badge badge-success">Actualizado</span>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
   
</x-layout>