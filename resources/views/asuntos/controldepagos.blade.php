<x-layout title="PostUDO || Pagos">

    <div class="content_texto_bienvenida">
        <label>Listado de Pagos</label>
    </div>

    {{-- ===== Barra de filtros (GET: afectan a la tabla de abajo) ===== --}}
    <div class="pagos-filtros">
        <form method="GET" action="{{ route('pago.controlpagos') }}" class="pagos-filtros-forma">
            {{-- Estado actual: conserva el filtro al pulsar «Aplicar».
                 Va ANTES que los botones, así gana el valor del botón
                 pulsado (último en la cadena de la query). --}}
            <input type="hidden" name="estado" value="{{ $estado }}">

            <div class="pagos-estados">
                <button type="submit" name="estado" value="todos" data-estado="todos"
                    class="pagos-estado {{ $estado === 'todos' ? 'pagos-estado--activo' : '' }}">
                    <i class="fa-solid fa-list"></i> Todos
                </button>
                <button type="submit" name="estado" value="pendiente" data-estado="pendiente"
                    class="pagos-estado {{ $estado === 'pendiente' ? 'pagos-estado--activo' : '' }}">
                    <i class="fa-solid fa-clock"></i> Pendientes
                </button>
                <button type="submit" name="estado" value="verificado" data-estado="verificado"
                    class="pagos-estado {{ $estado === 'verificado' ? 'pagos-estado--activo' : '' }}">
                    <i class="fa-solid fa-circle-check"></i> Verificados
                </button>
            </div>

            <label class="pagos-filtro-campo">
                <span>Postgrado</span>
                <select name="postgrado" class="pagos-filtro-control">
                    <option value="">Todos los postgrados</option>
                    @foreach ($postgrados as $item)
                        <option value="{{ $item->id_postgrado }}"
                            {{ $postgrado == $item->id_postgrado ? 'selected' : '' }}>
                            {{ $item->nombre }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label class="pagos-filtro-campo">
                <span>Semana (elige un día)</span>
                <input type="date" name="semana" value="{{ $semana }}" class="pagos-filtro-control">
            </label>

            <button type="submit" class="button_body pagos-filtro-aplicar">
                <i class="fa-solid fa-filter icon-left"></i> Aplicar filtros
            </button>

            <a href="{{ route('pago.controlpagos') }}" class="pagos-filtro-limpiar">
                <i class="fa-solid fa-xmark"></i> Limpiar
            </a>
        </form>

        <div class="pagos-contador">
            <span><strong>{{ $pagos->count() }}</strong>
                {{ $pagos->count() === 1 ? 'pago encontrado' : 'pagos encontrados' }}</span>
            @if ($rangoSemana)
                <span class="pagos-contador-rango">
                    <i class="fa-solid fa-calendar-week"></i> Semana del {{ $rangoSemana }}
                </span>
            @endif
        </div>
    </div>

    {{-- ===== Configuración del Google Sheet (movida desde Pagos Pendientes) ===== --}}
    @php
        $sheetConfig = null;
        if (\Storage::exists('google_sheet_config.json')) {
            $sheetConfig = json_decode(\Storage::get('google_sheet_config.json'), true);
        }
    @endphp

    <div class="sheet-config-section">
        <div class="sheet-config-header" id="toggleSheetConfig">
            <i class="fa-solid fa-gear"></i>
            <span>Configurar Google Sheet</span>
            <i class="fa-solid fa-chevron-down sheet-config-arrow" id="arrowIcon"></i>
        </div>
        <div class="sheet-config-body" id="sheetConfigBody">
            <form action="{{ route('pago.configurarSheet') }}" method="POST" class="sheet-config-form">
                @csrf
                <div class="sheet-config-input-group">
                    <label for="sheet_url" class="form-label">URL del Google Sheet (publicado como CSV)</label>
                    <input type="url" id="sheet_url" name="sheet_url" class="form-input"
                        placeholder="https://docs.google.com/spreadsheets/d/.../export?format=csv"
                        value="{{ $sheetConfig['url'] ?? '' }}" required>
                    <small class="sheet-config-help">
                        <i class="fa-solid fa-circle-info"></i>
                        En Google Sheets: Archivo → Compartir → Publicar en la web → CSV
                    </small>
                </div>
                <button type="submit" class="button_body">
                    <i class="fa-solid fa-save icon-left"></i> Guardar URL
                </button>
            </form>
            @if ($sheetConfig)
                <div class="sheet-config-status">
                    <i class="fa-solid fa-circle-check" style="color: #5de98a;"></i>
                    <span>Sheet configurado el {{ $sheetConfig['fecha_configuracion'] ?? 'N/A' }}</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Botón de verificación contra la hoja --}}
    <div class="action-buttons-container">
        @if ($sheetConfig && !empty($sheetConfig['url']))
            <a href="{{ route('pago.verificar') }}" class="button_body btn-verificar-principal">
                <i class="fa-solid fa-magnifying-glass-chart icon-left"></i> Verificar Pagos con Google Sheet
            </a>
        @else
            <button class="button_body" disabled style="opacity: 0.4; cursor: not-allowed;">
                <i class="fa-solid fa-magnifying-glass-chart icon-left"></i> Verificar Pagos (Configure el Sheet primero)
            </button>
        @endif
    </div>

    {{-- ===== Tabla de pagos (filtrada por los controles de arriba) ===== --}}
    <div class="action-buttons-container">
        <div>
            <table>
                <thead>
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Mención</th>
                        <th>Banco Emisor</th>
                        <th>Banco Receptor</th>
                        <th>Referencia</th>
                        <th>Monto (Bs.)</th>
                        <th>Asunto</th>
                        <th>Fecha de Pago</th>
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
                            <td>{{ $pago->estudiante?->especialidad ?? 'N/A' }}</td>
                            <td>{{ $pago->banco_emisor }}</td>
                            <td>{{ $pago->banco_receptor }}</td>
                            <td>{{ $pago->referencia }}</td>
                            <td>{{ number_format($pago->monto, 2, ',', '.') }}</td>
                            <td>{{ $pago->asunto }}</td>
                            <td>{{ $pago->fecha_pago ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') : '—' }}</td>
                            <td>{{ $pago->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $pago->estado }}</td>
                            <td>
                                @if ($pago->estado === 'Pendiente')
                                    <form action="{{ route('pago.actualizar', $pago->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="button_body">Actualizar</button>
                                    </form>
                                @else
                                    <span class="badge badge-success">Actualizado</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" style="text-align: center; padding: 20px;">
                                No hay pagos que coincidan con los filtros.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        /* ===== Filtros ===== */
        .pagos-filtros {
            width: 90%;
            max-width: 1100px;
            margin: 20px auto;
            padding: 16px 20px;
            background: rgba(0, 10, 32, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
        }

        .pagos-filtros-forma {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: 14px;
        }

        .pagos-estados {
            display: flex;
            gap: 6px;
            background: rgba(0, 10, 32, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 11px;
            padding: 5px;
        }

        .pagos-estado {
            display: flex;
            align-items: center;
            gap: 7px;
            background: transparent;
            border: none;
            border-radius: 9px;
            padding: 9px 16px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.88rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.55);
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .pagos-estado:hover {
            color: #ffffff;
        }

        .pagos-estado--activo {
            color: #ffffff;
            font-weight: 600;
        }

        .pagos-estado--activo[data-estado="todos"] {
            background: linear-gradient(135deg, #001233, #003da5);
            box-shadow: 0 4px 12px rgba(0, 61, 165, 0.4);
        }

        .pagos-estado--activo[data-estado="pendiente"] {
            background: linear-gradient(135deg, #d64545, #ff6b6b);
            box-shadow: 0 4px 12px rgba(255, 107, 107, 0.35);
        }

        .pagos-estado--activo[data-estado="verificado"] {
            background: linear-gradient(135deg, #1f9d55, #2ecc71);
            box-shadow: 0 4px 12px rgba(46, 204, 113, 0.35);
        }

        .pagos-filtro-campo {
            display: flex;
            flex-direction: column;
            gap: 6px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .pagos-filtro-control {
            background: rgba(0, 10, 32, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 9px;
            padding: 9px 12px;
            color: #ffffff;
            font-family: 'Outfit', sans-serif;
            font-size: 0.9rem;
            min-height: 39px;
        }

        .pagos-filtro-control:focus {
            outline: none;
            border-color: var(--accent-orange, #f7941d);
        }

        /* el datepicker nativo pinta texto oscuro sobre el fondo oscuro */
        .pagos-filtro-control::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }

        .pagos-filtro-aplicar {
            min-height: 39px;
        }

        .pagos-filtro-limpiar {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 14px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 9px;
            color: rgba(255, 255, 255, 0.7);
            font-family: 'Outfit', sans-serif;
            font-size: 0.88rem;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .pagos-filtro-limpiar:hover {
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.45);
        }

        .pagos-contador {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 16px;
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            font-family: 'Outfit', sans-serif;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .pagos-contador strong {
            color: #ffffff;
        }

        .pagos-contador-rango {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(247, 148, 29, 0.1);
            border: 1px solid rgba(247, 148, 29, 0.3);
            border-radius: 50px;
            padding: 4px 14px;
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.85rem;
        }

        .pagos-contador-rango i {
            color: #f7941d;
        }

        /* ===== Configuración del Sheet ===== */
        .sheet-config-section {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .sheet-config-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 16px 20px;
            cursor: pointer;
            font-family: 'Outfit', sans-serif;
            font-size: 1rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
            user-select: none;
        }

        .sheet-config-header:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        .sheet-config-header i:first-child {
            color: #f7941d;
            font-size: 1.1rem;
        }

        .sheet-config-arrow {
            margin-left: auto;
            transition: transform 0.3s ease;
            font-size: 0.8rem;
        }

        .sheet-config-arrow.rotated {
            transform: rotate(180deg);
        }

        .sheet-config-body {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease, padding 0.4s ease;
            padding: 0 20px;
        }

        .sheet-config-body.open {
            max-height: 400px;
            padding: 0 20px 20px 20px;
        }

        .sheet-config-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .sheet-config-input-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .sheet-config-help {
            font-family: 'Outfit', sans-serif;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.4);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .sheet-config-status {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-family: 'Outfit', sans-serif;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .btn-verificar-principal {
            background: linear-gradient(135deg, #001233, #003da5) !important;
            border-color: rgba(93, 148, 233, 0.3) !important;
            font-size: 1.05rem !important;
            padding: 14px 30px !important;
        }

        .btn-verificar-principal:hover {
            box-shadow: 0 10px 30px rgba(93, 148, 233, 0.3) !important;
            border-color: rgba(93, 148, 233, 0.6) !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('toggleSheetConfig');
            const body = document.getElementById('sheetConfigBody');
            const arrow = document.getElementById('arrowIcon');

            toggle.addEventListener('click', function () {
                body.classList.toggle('open');
                arrow.classList.toggle('rotated');
            });

            // Abrir automáticamente si no hay URL configurada
            @if (!$sheetConfig || empty($sheetConfig['url']))
                body.classList.add('open');
                arrow.classList.add('rotated');
            @endif
        });
    </script>

</x-layout>
