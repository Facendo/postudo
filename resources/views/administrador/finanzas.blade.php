<x-layout title='PostUDO || Ingresos y Egresos'>

    <div class="page-content"
        style="width: 90%; max-width: 1200px; margin: 40px auto; display: flex; flex-direction: column; align-items: center;">

        {{-- Título de la sección --}}
        <div class="content_texto_bienvenida">
            <label>Ingresos y Egresos</label>
        </div>

        {{-- Resumen de totales --}}
        <div class="finanzas-resumen">
            <div class="finanza-card finanza-card--ingreso">
                <i class="fa-solid fa-arrow-trend-up"></i>
                <div class="finanza-info">
                    <span class="finanza-label">Total Ingresos</span>
                    <span class="finanza-valor">Bs. {{ number_format($totalIngresos, 2, ',', '.') }}</span>
                    <span class="finanza-sub">Pagos verificados: {{ $ingresos->count() }}</span>
                </div>
            </div>

            <div class="finanza-card finanza-card--egreso">
                <i class="fa-solid fa-arrow-trend-down"></i>
                <div class="finanza-info">
                    <span class="finanza-label">Total Egresos</span>
                    <span class="finanza-valor">Bs. {{ number_format($totalEgresos, 2, ',', '.') }}</span>
                    <span class="finanza-sub">Registros: {{ $egresos->count() }}</span>
                </div>
            </div>

            <div class="finanza-card {{ $balance >= 0 ? 'finanza-card--positivo' : 'finanza-card--negativo' }}">
                <i class="fa-solid fa-scale-balanced"></i>
                <div class="finanza-info">
                    <span class="finanza-label">Balance</span>
                    <span class="finanza-valor">Bs. {{ number_format($balance, 2, ',', '.') }}</span>
                    <span class="finanza-sub">Ingresos - Egresos</span>
                </div>
            </div>
        </div>

        {{-- Barra de herramientas: pestañas + botón registrar egreso --}}
        <div class="finanzas-toolbar">
            <div class="finanzas-tabs">
                <button type="button" class="finanzas-tab finanzas-tab--activo" data-tab="ingresos">
                    <i class="fa-solid fa-arrow-trend-up"></i> Ingresos
                </button>
                <button type="button" class="finanzas-tab" data-tab="egresos">
                    <i class="fa-solid fa-arrow-trend-down"></i> Egresos
                </button>
            </div>

            <a href="{{ route('administrador.egreso.create') }}" class="button_body">
                <i class="fa-solid fa-plus icon-left"></i> Registrar Egreso
            </a>
        </div>

        {{-- Panel: Ingresos --}}
        <div id="panel-ingresos" class="finanzas-panel finanzas-panel--activo">
            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha de Pago</th>
                        <th>Estudiante</th>
                        <th>Cédula</th>
                        <th>Asunto</th>
                        <th>Referencia</th>
                        <th style="text-align:center;">Monto (Bs.)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ingresos as $ingreso)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($ingreso->fecha_pago)->format('d/m/Y') }}</td>
                            <td>{{ $ingreso->nombre }}</td>
                            <td>{{ $ingreso->cedula }}</td>
                            <td>{{ $ingreso->asunto }}</td>
                            <td>{{ $ingreso->referencia }}</td>
                            <td style="text-align:center;">
                                <span class="monto-ingreso">Bs. {{ number_format($ingreso->monto, 2, ',', '.') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px;">
                                No hay ingresos verificados registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Panel: Egresos --}}
        <div id="panel-egresos" class="finanzas-panel">
            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Motivo</th>
                        <th>Detalle</th>
                        <th style="text-align:center;">Monto (Bs.)</th>
                        <th>Registrado por</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($egresos as $egreso)
                        <tr>
                            <td>{{ $egreso->fecha->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge-motivo">{{ $egreso->motivo }}</span>
                            </td>
                            <td>{{ $egreso->detalle ?? '—' }}</td>
                            <td style="text-align:center;">
                                <span class="monto-egreso">Bs. {{ number_format($egreso->monto, 2, ',', '.') }}</span>
                            </td>
                            <td>{{ $egreso->usuario->name ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px;">
                                No hay egresos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        /* ===== Resumen de totales ===== */
        .finanzas-resumen {
            display: flex;
            gap: 20px;
            width: 100%;
            max-width: 1000px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }

        .finanza-card {
            flex: 1;
            min-width: 220px;
            display: flex;
            align-items: center;
            gap: 14px;
            background: rgba(15, 5, 5, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 18px 20px;
        }

        .finanza-card i {
            font-size: 1.5rem;
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .finanza-card--ingreso i {
            color: #5de98a;
            background: rgba(93, 233, 138, 0.12);
        }

        .finanza-card--egreso i {
            color: #ff6b6b;
            background: rgba(255, 107, 107, 0.12);
        }

        .finanza-card--positivo i {
            color: #6dd5fa;
            background: rgba(109, 213, 250, 0.12);
        }

        .finanza-card--negativo i {
            color: #ffc107;
            background: rgba(255, 193, 7, 0.12);
        }

        .finanza-info {
            display: flex;
            flex-direction: column;
        }

        .finanza-label {
            font-family: 'Outfit', sans-serif;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.5);
            letter-spacing: 0.3px;
        }

        .finanza-valor {
            font-family: 'Outfit', sans-serif;
            font-size: 1.35rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.2;
        }

        .finanza-sub {
            font-family: 'Outfit', sans-serif;
            font-size: 0.72rem;
            color: rgba(255, 255, 255, 0.35);
        }

        /* ===== Toolbar con pestañas ===== */
        .finanzas-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            width: 100%;
            max-width: 1000px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .finanzas-tabs {
            display: flex;
            gap: 8px;
            background: rgba(15, 5, 5, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 5px;
        }

        .finanzas-tab {
            display: flex;
            align-items: center;
            gap: 7px;
            background: transparent;
            border: none;
            border-radius: 9px;
            padding: 9px 18px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.88rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.55);
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .finanzas-tab:hover {
            color: #ffffff;
        }

        .finanzas-tab--activo {
            background: linear-gradient(135deg, #cc0000, #ff4d4d);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(204, 0, 0, 0.35);
        }

        /* ===== Paneles de tablas ===== */
        .finanzas-panel {
            display: none;
            width: 100%;
        }

        .finanzas-panel--activo {
            display: block;
        }

        .monto-ingreso {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            color: #5de98a;
        }

        .monto-egreso {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            color: #ff6b6b;
        }

        .badge-motivo {
            display: inline-block;
            background: rgba(255, 77, 77, 0.1);
            border: 1px solid rgba(255, 77, 77, 0.25);
            border-radius: 50px;
            padding: 3px 12px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.8);
        }
    </style>

    <script>
        (function () {
            const tabs = document.querySelectorAll('.finanzas-tab');
            const panels = {
                ingresos: document.getElementById('panel-ingresos'),
                egresos: document.getElementById('panel-egresos'),
            };

            tabs.forEach(function (tab) {
                tab.addEventListener('click', function () {
                    // Actualizar pestaña activa
                    tabs.forEach(t => t.classList.remove('finanzas-tab--activo'));
                    tab.classList.add('finanzas-tab');

                    // Mostrar el panel correspondiente
                    Object.values(panels).forEach(p => p.classList.remove('finanzas-panel--activo'));
                    panels[tab.dataset.tab].classList.add('finanzas-panel--activo');
                });
            });
        })();
    </script>

</x-layout>
