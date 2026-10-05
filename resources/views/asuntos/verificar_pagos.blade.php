<x-layout title="Verificar Pagos">

    <div class="content_texto_bienvenida">
        <label>Verificación de Pagos</label>
    </div>

    <div class="verificacion-container">

        {{-- Resumen de resultados --}}
        @php
            $totalVerificados = collect($resultados)->where('estado_verificacion', 'verificado')->count();
            $totalParciales = collect($resultados)->whereIn('estado_verificacion', ['monto_diferente', 'fecha_diferente', 'monto_fecha_diferente'])->count();
            $totalNoEncontrados = collect($resultados)->where('estado_verificacion', 'no_encontrado')->count();
        @endphp

        <div class="verificacion-resumen">
            <div class="resumen-card resumen-verificado">
                <i class="fa-solid fa-circle-check"></i>
                <span class="resumen-numero">{{ $totalVerificados }}</span>
                <span class="resumen-label">Verificados</span>
            </div>
            <div class="resumen-card resumen-parcial">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span class="resumen-numero">{{ $totalParciales }}</span>
                <span class="resumen-label">Con diferencias</span>
            </div>
            <div class="resumen-card resumen-no-encontrado">
                <i class="fa-solid fa-circle-xmark"></i>
                <span class="resumen-numero">{{ $totalNoEncontrados }}</span>
                <span class="resumen-label">No encontrados</span>
            </div>
        </div>

        @if(count($resultados) === 0)
            <div class="verificacion-vacio">
                <i class="fa-solid fa-inbox"></i>
                <p>No hay pagos pendientes para verificar.</p>
            </div>
        @else
            <form action="{{ route('pago.confirmarVerificados') }}" method="POST" id="formVerificados">
                @csrf

                {{-- Botones de acción --}}
                <div class="verificacion-acciones">
                    <button type="button" id="btnSeleccionarVerificados" class="button_body btn-verificar-accion">
                        <i class="fa-solid fa-check-double icon-left"></i> Seleccionar todos los verificados
                    </button>
                    <button type="submit" class="button_body btn-confirmar-accion" id="btnConfirmar">
                        <i class="fa-solid fa-paper-plane icon-left"></i> Confirmar seleccionados (<span
                            id="contadorSeleccionados">0</span>)
                    </button>
                </div>

                {{-- Tabla de resultados --}}
                <div class="verificacion-tabla-wrapper">
                    <table class="verificacion-tabla">
                        <thead>
                            <tr>
                                <th class="th-check"><input type="checkbox" id="checkAll" title="Seleccionar todo"></th>
                                <th>Estado</th>
                                <th>Cédula</th>
                                <th>Nombre</th>
                                <th>Especialidad</th>
                                <th>Referencia</th>
                                <th>Asunto</th>
                                <th>Monto Registrado</th>
                                <th>Monto Esperado</th>
                                <th>Monto en Sheet</th>
                                <th>Fecha Pago</th>
                                <th>Fecha en Sheet</th>
                                <th>Detalle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resultados as $resultado)
                                @php
                                    $pago = $resultado['pago'];
                                    $estado = $resultado['estado_verificacion'];
                                @endphp
                                <tr class="fila-{{ $estado }}">
                                    <td class="td-check">
                                        <input type="checkbox" name="pago_ids[]" value="{{ $pago->id }}" class="check-pago"
                                            data-estado="{{ $estado }}">
                                    </td>
                                    <td>
                                        @if($estado === 'verificado')
                                            <span class="badge-verificacion badge-ok">
                                                <i class="fa-solid fa-circle-check"></i> Verificado
                                            </span>
                                        @elseif($estado === 'monto_diferente')
                                            <span class="badge-verificacion badge-warn">
                                                <i class="fa-solid fa-triangle-exclamation"></i> Monto ≠
                                            </span>
                                        @elseif($estado === 'fecha_diferente')
                                            <span class="badge-verificacion badge-warn">
                                                <i class="fa-solid fa-triangle-exclamation"></i> Fecha ≠
                                            </span>
                                        @elseif($estado === 'monto_fecha_diferente')
                                            <span class="badge-verificacion badge-warn">
                                                <i class="fa-solid fa-triangle-exclamation"></i> Monto y Fecha ≠
                                            </span>
                                        @else
                                            <span class="badge-verificacion badge-error">
                                                <i class="fa-solid fa-circle-xmark"></i> No encontrado
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $pago->cedula }}</td>
                                    <td>{{ $pago->nombre }}</td>
                                    <td>{{ $pago->estudiante->especialidadRel->nombre ?? $pago->estudiante->especialidad ?? 'N/A' }}
                                    </td>
                                    <td class="celda-referencia">{{ $pago->referencia }}</td>
                                    <td>{{ $pago->asunto }}</td>
                                    <td>{{ number_format($pago->monto, 2, ',', '.') }} Bs.</td>
                                    <td>
                                        @if($resultado['monto_esperado'] > 0)
                                            {{ number_format($resultado['monto_esperado'], 2, ',', '.') }} Bs.
                                        @else
                                            <span class="text-muted-light">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($resultado['monto_sheet'] !== null)
                                            <span class="{{ $resultado['monto_coincide'] ? 'text-ok' : 'text-warn' }}">
                                                {{ number_format($resultado['monto_sheet'], 2, ',', '.') }} Bs.
                                            </span>
                                        @else
                                            <span class="text-muted-light">—</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                                    <td>
                                        @if($resultado['fecha_sheet'] !== null)
                                            <span class="{{ $resultado['fecha_coincide'] ? 'text-ok' : 'text-warn' }}">
                                                {{ $resultado['fecha_sheet'] }}
                                            </span>
                                        @else
                                            <span class="text-muted-light">—</span>
                                        @endif
                                    </td>
                                    <td class="celda-detalle">
                                        @if($estado === 'verificado')
                                            <span class="text-ok">Todo coincide</span>
                                        @elseif($estado === 'no_encontrado')
                                            <span class="text-error">Referencia no existe en Sheet</span>
                                        @elseif($estado === 'monto_diferente')
                                            <span class="text-warn">El monto no coincide con lo esperado</span>
                                        @elseif($estado === 'fecha_diferente')
                                            <span class="text-warn">La fecha no coincide</span>
                                        @else
                                            <span class="text-warn">Monto y fecha no coinciden</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        @endif

        <div class="verificacion-volver">
            <a href="{{ route('pago.pendientes') }}" class="button_body">
                <i class="fa-solid fa-arrow-left icon-left"></i> Volver a Pagos Pendientes
            </a>
        </div>
    </div>

    <style>
        .verificacion-container {
            width: 95%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Resumen cards */
        .verificacion-resumen {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .resumen-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-radius: 16px;
            padding: 20px 30px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            min-width: 160px;
            transition: all 0.3s ease;
        }

        .resumen-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .resumen-card i {
            font-size: 2rem;
        }

        .resumen-numero {
            font-family: 'Outfit', sans-serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: #fff;
        }

        .resumen-label {
            font-family: 'Outfit', sans-serif;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .resumen-verificado {
            border-color: rgba(93, 233, 138, 0.3);
        }

        .resumen-verificado i {
            color: #5de98a;
        }

        .resumen-parcial {
            border-color: rgba(255, 193, 7, 0.3);
        }

        .resumen-parcial i {
            color: #ffc107;
        }

        .resumen-no-encontrado {
            border-color: rgba(255, 77, 77, 0.3);
        }

        .resumen-no-encontrado i {
            color: #ff4d4d;
        }

        /* Acciones */
        .verificacion-acciones {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .btn-verificar-accion {
            border-color: rgba(93, 233, 138, 0.3) !important;
        }

        .btn-verificar-accion:hover {
            border-color: rgba(93, 233, 138, 0.6) !important;
        }

        .btn-confirmar-accion {
            background: linear-gradient(135deg, #1a5c2e, #2d8a4e) !important;
            border-color: rgba(93, 233, 138, 0.3) !important;
        }

        .btn-confirmar-accion:hover {
            box-shadow: 0 8px 25px rgba(93, 233, 138, 0.3) !important;
        }

        /* Tabla */
        .verificacion-tabla-wrapper {
            overflow-x: auto;
            border-radius: 15px;
        }

        .verificacion-tabla {
            min-width: 1200px;
        }

        .th-check,
        .td-check {
            width: 40px;
            text-align: center !important;
        }

        .td-check input[type="checkbox"],
        .th-check input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #5de98a;
        }

        /* Filas por estado */
        .fila-verificado {
            border-left: 3px solid #5de98a;
        }

        .fila-monto_diferente,
        .fila-fecha_diferente,
        .fila-monto_fecha_diferente {
            border-left: 3px solid #ffc107;
        }

        .fila-no_encontrado {
            border-left: 3px solid #ff4d4d;
            opacity: 0.7;
        }

        /* Badges */
        .badge-verificacion {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.82rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-ok {
            background: rgba(93, 233, 138, 0.15);
            color: #5de98a;
            border: 1px solid rgba(93, 233, 138, 0.3);
        }

        .badge-warn {
            background: rgba(255, 193, 7, 0.15);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .badge-error {
            background: rgba(255, 77, 77, 0.15);
            color: #ff4d4d;
            border: 1px solid rgba(255, 77, 77, 0.3);
        }

        /* Textos de estado */
        .text-ok {
            color: #5de98a;
            font-weight: 600;
        }

        .text-warn {
            color: #ffc107;
            font-weight: 600;
        }

        .text-error {
            color: #ff4d4d;
            font-weight: 600;
        }

        .text-muted-light {
            color: rgba(255, 255, 255, 0.3);
        }

        .celda-referencia {
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
        }

        .celda-detalle {
            font-size: 0.85rem;
            max-width: 180px;
        }

        /* Volver */
        .verificacion-volver {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        /* Vacío */
        .verificacion-vacio {
            text-align: center;
            padding: 60px 20px;
            color: rgba(255, 255, 255, 0.5);
        }

        .verificacion-vacio i {
            font-size: 3rem;
            margin-bottom: 15px;
            display: block;
        }

        .verificacion-vacio p {
            font-family: 'Outfit', sans-serif;
            font-size: 1.1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .verificacion-resumen {
                flex-direction: column;
                align-items: center;
            }

            .resumen-card {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkAll = document.getElementById('checkAll');
            const checkboxes = document.querySelectorAll('.check-pago');
            const contador = document.getElementById('contadorSeleccionados');
            const btnSeleccionarVerificados = document.getElementById('btnSeleccionarVerificados');
            const btnConfirmar = document.getElementById('btnConfirmar');

            function actualizarContador() {
                const seleccionados = document.querySelectorAll('.check-pago:checked').length;
                contador.textContent = seleccionados;
                btnConfirmar.disabled = seleccionados === 0;
                if (seleccionados === 0) {
                    btnConfirmar.style.opacity = '0.5';
                } else {
                    btnConfirmar.style.opacity = '1';
                }
            }

            // Seleccionar/deseleccionar todos
            if (checkAll) {
                checkAll.addEventListener('change', function () {
                    checkboxes.forEach(cb => {
                        cb.checked = checkAll.checked;
                    });
                    actualizarContador();
                });
            }

            // Actualizar contador al cambiar individual
            checkboxes.forEach(cb => {
                cb.addEventListener('change', actualizarContador);
            });

            // Seleccionar solo los verificados
            if (btnSeleccionarVerificados) {
                btnSeleccionarVerificados.addEventListener('click', function () {
                    checkboxes.forEach(cb => {
                        cb.checked = cb.dataset.estado === 'verificado';
                    });
                    actualizarContador();
                });
            }

            // Confirmación antes de enviar
            if (btnConfirmar) {
                btnConfirmar.closest('form').addEventListener('submit', function (e) {
                    const seleccionados = document.querySelectorAll('.check-pago:checked').length;
                    if (seleccionados === 0) {
                        e.preventDefault();
                        alert('Seleccione al menos un pago para confirmar.');
                        return;
                    }
                    if (!confirm('¿Está seguro de actualizar ' + seleccionados + ' pago(s) como "Actualizado"?')) {
                        e.preventDefault();
                    }
                });
            }

            actualizarContador();
        });
    </script>

</x-layout>