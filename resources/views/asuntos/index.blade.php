<x-layout title='PostUDO || Asuntos'>

    <div class="page-content"
        style="width: 90%; max-width: 1200px; margin: 40px auto; display: flex; flex-direction: column; align-items: center;">
        {{-- Título de la sección --}}
        <div class="content_texto_bienvenida">
            <label>Listado de Asuntos</label>
        </div>

        {{-- Tasa de cambio vigente --}}
        @php
            $tasaJson = \Storage::exists('tasa_cambio.json')
                ? json_decode(\Storage::get('tasa_cambio.json'), true)
                : null;
            $tasaValor = $tasaJson['valor'] ?? null;
        @endphp

        <div class="asuntos-toolbar">
            <a href="{{ route('asuntos.create') }}" class="button_body">
                <i class="fa-solid fa-plus icon-left"></i> Nuevo Asunto
            </a>

            @if($tasaValor)
                <div class="tasa-badge">
                    <i class="fa-solid fa-dollar-sign"></i>
                    Tasa vigente: <strong>Bs. {{ number_format($tasaValor, 2, ',', '.') }} / $</strong>
                    <span class="tasa-badge-fecha">({{ $tasaJson['fecha'] ?? '' }} · {{ $tasaJson['hora'] ?? '' }})</span>
                </div>
            @else
                <div class="tasa-badge tasa-badge--warn">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Sin tasa de cambio registrada.
                </div>
            @endif
        </div>

        {{-- Tabla para mostrar los asuntos --}}
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th style="text-align:center;">Valor (USD)</th>
                    @if($tasaValor)
                        <th style="text-align:center;">Equivalente (Bs.)</th>
                    @endif
                    <th style="text-align:center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($asuntos as $asunto)
                    <tr>
                        <td>{{ $asunto->id }}</td>
                        <td>{{ $asunto->nombre }}</td>
                        <td>{{ $asunto->descripcion }}</td>

                        {{-- Precio en USD --}}
                        <td style="text-align:center;">
                            @if($asunto->valor_usd && $asunto->valor_usd > 0)
                                <span class="precio-usd">
                                    $ {{ number_format($asunto->valor_usd, 2, ',', '.') }}
                                </span>
                            @else
                                <span style="color:rgba(255,255,255,0.3); font-size:0.85rem;">—</span>
                            @endif
                        </td>

                        {{-- Equivalente en Bolívares --}}
                        @if($tasaValor)
                            <td style="text-align:center;">
                                @if($asunto->valor_usd && $asunto->valor_usd > 0)
                                    <span class="precio-bs">
                                        Bs. {{ number_format($asunto->valor_usd * $tasaValor, 2, ',', '.') }}
                                    </span>
                                @else
                                    <span style="color:rgba(255,255,255,0.3); font-size:0.85rem;">—</span>
                                @endif
                            </td>
                        @endif

                        {{-- Acciones --}}
                        <td class="table-actions" style="text-align:center;">
                            <a href="{{ route('asuntos.edit', $asunto->id) }}" class="btn-table-action" title="Editar">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <form action="{{ route('asuntos.destroy', $asunto->id) }}" method="POST" class="inline-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-table-action" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $tasaValor ? 6 : 5 }}" style="text-align: center; padding: 20px;">
                            No hay asuntos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <style>
        .asuntos-toolbar {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            margin-bottom: 24px;
        }

        .tasa-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 77, 77, 0.1);
            border: 1px solid rgba(255, 77, 77, 0.25);
            border-radius: 50px;
            padding: 8px 20px;
            margin-bottom: 20px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.88rem;
            color: rgba(255, 255, 255, 0.75);
        }

        .tasa-badge i {
            color: #ff4d4d;
        }

        .tasa-badge strong {
            color: #ffffff;
        }

        .tasa-badge-fecha {
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.8rem;
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
            color: #5de98a;
            font-size: 0.95rem;
        }

        .precio-bs {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            color: #6dd5fa;
            font-size: 0.95rem;
        }
    </style>

</x-layout>