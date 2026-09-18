<x-layout title="PostUDO || Mis Pagos">


        <div class="content_texto_bienvenida">
            <label>Mis pagos</label>
        </div>
    <div>
        <table>
            <thead>
                <tr>
                    <th>Banco Emisor</th>
                    <th>Banco Receptor</th>
                    <th>Referencia</th>
                    <th>Monto (Bs.)</th>
                    <th>Asunto</th>
                    <th>Fecha de Registro</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pagos as $pago)
                    <tr>
                        <td>{{ $pago->banco_emisor }}</td>
                        <td>{{ $pago->banco_receptor }}</td>
                        <td>{{ $pago->referencia }}</td>
                        <td>{{ number_format($pago->monto, 2, ',', '.') }}</td>
                        <td>{{ $pago->asunto }}</td>
                        <td>{{ $pago->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $pago->estado }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 20px; color: rgba(255,255,255,0.6);">
                            No tiene pagos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layout>