<x-layout title="Pagos Pendientes">

    <div class="content_texto_bienvenida">
        <label>Pagos Pendientes</label>
    </div>

    {{-- Sección de configuración del Google Sheet --}}
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
            @if($sheetConfig)
                <div class="sheet-config-status">
                    <i class="fa-solid fa-circle-check" style="color: #5de98a;"></i>
                    <span>Sheet configurado el {{ $sheetConfig['fecha_configuracion'] ?? 'N/A' }}</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Botón de verificación --}}
    <div class="action-buttons-container">
        @if($sheetConfig && !empty($sheetConfig['url']))
            <a href="{{ route('pago.verificar') }}" class="button_body btn-verificar-principal">
                <i class="fa-solid fa-magnifying-glass-chart icon-left"></i> Verificar Pagos con Google Sheet
            </a>
        @else
            <button class="button_body" disabled style="opacity: 0.4; cursor: not-allowed;">
                <i class="fa-solid fa-magnifying-glass-chart icon-left"></i> Verificar Pagos (Configure el Sheet primero)
            </button>
        @endif
    </div>

    {{-- Tabla de pagos pendientes --}}
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
                            <td>{{ $pago->estudiante->especialidadRel->nombre ?? $pago->estudiante->especialidad ?? 'N/A' }}
                            </td>
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
                            <td colspan="11" style="text-align: center; padding: 2rem; opacity: 0.7;">No hay pagos
                                pendientes.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        /* Sheet Config Section */
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
            color: #ff4d4d;
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

        /* Botón de verificar principal */
        .btn-verificar-principal {
            background: linear-gradient(135deg, #1a3a5c, #2d5a8a) !important;
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
            @if(!$sheetConfig || empty($sheetConfig['url']))
                body.classList.add('open');
                arrow.classList.add('rotated');
            @endif
        });
    </script>

</x-layout>