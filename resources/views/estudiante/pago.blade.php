<x-layout title="Panel de Pago">
        <h1>Lista de Pagos</h1>
        <div class="action-buttons-container">
            <a href="{{ route('pago.create') }}" class="button_body">
                <i class="fa-solid fa-plus icon-left"></i> Registrar Nuevo Pago
            </a>
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
   
</x-layout>