<x-layout title="Precios de Solicitudes">

    <div class="page-content"
        style="width: 90%; max-width: 1000px; margin: 40px auto; display: flex; flex-direction: column; align-items: center;">

        <div class="content_texto_bienvenida">
            <label>Precios de Solicitudes y Trámites</label>
        </div>

        @php
            $tasaJson = \Storage::exists('tasa_cambio.json')
                ? json_decode(\Storage::get('tasa_cambio.json'), true)
                : null;
            $tasaValor = $tasaJson['valor'] ?? null;
        @endphp

        <div class="asuntos-toolbar" style="margin-bottom: 30px;">
            @if($tasaValor)
                <div class="tasa-badge">
                    <i class="fa-solid fa-dollar-sign"></i>
                    Tasa del BCV actual: <strong>Bs. {{ number_format($tasaValor, 2, ',', '.') }} / $</strong>
                    <span class="tasa-badge-fecha">({{ $tasaJson['fecha'] ?? '' }} · {{ $tasaJson['hora'] ?? '' }})</span>
                </div>
                <p
                    style="text-align: center; color: rgba(255, 255, 255, 0.7); font-family: 'Outfit', sans-serif; font-size: 0.95rem; margin-top: -10px;">
                    El monto en bolívares se congelará utilizando la tasa vigente del día en que realices tu transferencia
                    bancaria.
                </p>
            @else
                <div class="tasa-badge tasa-badge--warn">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    No hay tasa de cambio registrada en el sistema actualmente.
                </div>
            @endif
        </div>

        <table class="table" style="box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);">
            <thead>
                <tr>
                    <th>Trámite / Solicitud</th>
                    <th>Descripción</th>
                    <th style="text-align:center;">Precio Referencial (USD)</th>
                    @if($tasaValor)
                        <th style="text-align:center;">Monto a transferir HOY (Bs.)</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($asuntos as $asunto)
                    <tr>
                        <td style="font-weight: 600;">{{ $asunto->nombre }}</td>
                        <td style="color: rgba(255, 255, 255, 0.8);">{{ $asunto->descripcion ?: 'Sin descripción' }}</td>

                        <td style="text-align:center;">
                            @if($asunto->valor_usd && $asunto->valor_usd > 0)
                                <span class="precio-usd">
                                    $ {{ number_format($asunto->valor_usd, 2, ',', '.') }}
                                </span>
                            @else
                                <span style="color:rgba(255,255,255,0.3); font-size:0.85rem;">Gratuito / No definido</span>
                            @endif
                        </td>

                        @if($tasaValor)
                            <td style="text-align:center;">
                                @if($asunto->valor_usd && $asunto->valor_usd > 0)
                                    <span class="precio-bs" style="font-size: 1.1rem;">
                                        Bs. {{ number_format($asunto->valor_usd * $tasaValor, 2, ',', '.') }}
                                    </span>
                                @else
                                    <span style="color:rgba(255,255,255,0.3); font-size:0.85rem;">—</span>
                                @endif
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $tasaValor ? 4 : 3 }}"
                            style="text-align: center; padding: 30px; color: rgba(255, 255, 255, 0.5);">
                            No hay información de trámites disponible por el momento.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 40px;">
            <a href="{{ route('inicio') }}" class="button_body">
                <i class="fa-solid fa-arrow-left icon-left"></i> Volver al panel
            </a>
        </div>
    </div>

    <style>
        .asuntos-toolbar {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .tasa-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(93, 233, 138, 0.1);
            border: 1px solid rgba(93, 233, 138, 0.3);
            border-radius: 50px;
            padding: 10px 25px;
            font-family: 'Outfit', sans-serif;
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 15px rgba(93, 233, 138, 0.1);
        }

        .tasa-badge i {
            color: #5de98a;
            font-size: 1.1rem;
        }

        .tasa-badge strong {
            color: #ffffff;
            font-weight: 700;
        }

        .tasa-badge-fecha {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.85rem;
            margin-left: 5px;
        }

        .tasa-badge--warn {
            background: rgba(255, 193, 7, 0.1);
            border-color: rgba(255, 193, 7, 0.3);
        }

        .tasa-badge--warn i {
            color: #ffc107;
        }

        .precio-usd {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            color: #ffb3b3;
            font-size: 1rem;
            background: rgba(255, 77, 77, 0.1);
            padding: 4px 10px;
            border-radius: 6px;
        }

        .precio-bs {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            color: #5de98a;
            text-shadow: 0 0 10px rgba(93, 233, 138, 0.2);
        }

        .table td {
            vertical-align: middle;
            padding: 20px;
        }
    </style>

</x-layout>