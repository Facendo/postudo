<x-layout title="Registrar Pago">
    <div class="registration-page-container">
        <form action="{{ route('pago.store') }}" method="POST" class="registration-form">
            @csrf
            <h2 class="registration-form-title">Registrar Nuevo Pago</h2>

            <div class="form-group">
                <label for="cedula_pago" class="form-label">Cédula del Remitente</label>
                <input type="text" id="cedula" name="cedula" class="form-input" placeholder="Ej: V-12345678"
                    value="{{ old('cedula', Auth::user()->cedula) }}" required>
                @error('cedula')
                    <span class="form-error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="nombre" class="form-label">Nombre Completo del Pagador</label>
                <input type="text" id="nombre" name="nombre" class="form-input" placeholder="Ej: Juan Pérez" required>
                @error('nombre')
                    <span class="form-error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="banco_emisor" class="form-label">Banco Emisor</label>
                <select id="banco_emisor" name="banco_emisor" class="form-input" required>
                    <option value="">Seleccione un banco</option>
                    <option value="Banco de Venezuela">Banco de Venezuela</option>
                    <option value="Banesco">Banesco</option>
                    <option value="Mercantil">Mercantil</option>
                    <option value="BBVA Provincial">BBVA Provincial</option>
                    <option value="Banco Nacional de Crédito">Banco Nacional de Crédito (BNC)</option>
                    <option value="Banco Activo">Banco Activo</option>
                    <option value="Banco Bicentenario del Pueblo">Banco Bicentenario del Pueblo</option>
                    <option value="Banco del Tesoro">Banco del Tesoro</option>
                    <option value="Banco Exterior">Banco Exterior</option>
                    <option value="Banco Fondo Común">Banco Fondo Común (BFC)</option>
                    <option value="Banco Caroní">Banco Caroní</option>
                    <option value="Banplus">Banplus</option>
                    <option value="Venezolano de Crédito">Venezolano de Crédito</option>
                    <option value="Banco Plaza">Banco Plaza</option>
                </select>
                @error('banco_emisor')
                    <span class="form-error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="banco_receptor" class="form-label">Banco Receptor</label>
                <select id="banco_receptor" name="banco_receptor" class="form-input" required>
                    <option value="">Seleccione un banco</option>
                    <option value="Banco de Venezuela">Banco de Venezuela</option>
                    <option value="Banesco">Banesco</option>
                    <option value="Mercantil">Mercantil</option>
                    <option value="BBVA Provincial">BBVA Provincial</option>
                    <option value="Banco Nacional de Crédito">Banco Nacional de Crédito (BNC)</option>
                    <option value="Banco Activo">Banco Activo</option>
                    <option value="Banco Bicentenario del Pueblo">Banco Bicentenario del Pueblo</option>
                    <option value="Banco del Tesoro">Banco del Tesoro</option>
                    <option value="Banco Exterior">Banco Exterior</option>
                    <option value="Banco Fondo Común">Banco Fondo Común (BFC)</option>
                    <option value="Banco Caroní">Banco Caroní</option>
                    <option value="Banplus">Banplus</option>
                    <option value="Venezolano de Crédito">Venezolano de Crédito</option>
                    <option value="Banco Plaza">Banco Plaza</option>
                </select>
                @error('banco_receptor')
                    <span class="form-error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="referencia" class="form-label">Referencia de Pago</label>
                <input type="text" id="referencia" name="referencia" class="form-input" placeholder="Ej: 000123456789"
                    required>
                @error('referencia')
                    <span class="form-error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="monto" class="form-label">Monto (Bs.)</label>
                <input type="number" id="monto" name="monto" class="form-input" step="0.01" min="0"
                    placeholder="Ej: 150.50" required>
                @error('monto')
                    <span class="form-error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group relative">
                <label for="asunto" class="form-label">Asunto / Descripción del Pago</label>
                <select id="asunto" name="asunto" class="form-input" required>
                    <option value="" data-valor-usd="0">Seleccione un asunto</option>
                    @foreach($asuntos as $asunto)
                        <option value="{{ $asunto->nombre }}" data-valor-usd="{{ $asunto->valor_usd ?? 0 }}">
                            {{ $asunto->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('asunto')
                    <span class="form-error-message">{{ $message }}</span>
                @enderror

                {{-- Cuadro dinámico con el precio (Oculto por defecto) --}}
                <div id="precioInfoBox" class="precio-info-box hidden">
                    <div class="precio-calc">
                        <div class="precio-item">
                            <span class="precio-label">Precio Asunto:</span>
                            <span class="precio-valor" id="precioUsdDisplay">$0.00</span>
                        </div>
                        <i class="fa-solid fa-xmark text-muted"></i>
                        <div class="precio-item">
                            <span class="precio-label">Tasa:</span>
                            <span class="precio-valor">Bs. <span id="tasaDisplay">0.00</span></span>
                        </div>
                    </div>
                    <div class="precio-total">
                        Total estimado a pagar: <strong class="text-success">Bs. <span
                                id="precioTotalBs">0.00</span></strong>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="fecha_pago" class="form-label">Fecha del Pago</label>
                <input type="date" id="fecha_pago" name="fecha_pago" class="form-input" required>
                @error('fecha_pago')
                    <span class="form-error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="button_body">
                    <i class="fa-solid fa-paper-plane icon-left"></i> Registrar Pago
                </button>
            </div>
        </form>
    </div>

    @php
        $tasaJson = \Storage::exists('tasa_cambio.json')
            ? json_decode(\Storage::get('tasa_cambio.json'), true)
            : null;
        $tasaActual = $tasaJson['valor'] ?? 0;
    @endphp

    <style>
        .hidden {
            display: none !important;
        }

        .precio-info-box {
            margin-top: 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 15px;
            animation: fadeInDown 0.3s ease-out forwards;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .precio-calc {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.2);
        }

        .precio-item {
            text-align: center;
        }

        .precio-label {
            display: block;
            font-family: 'Outfit', sans-serif;
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .precio-valor {
            font-family: 'Outfit', sans-serif;
            font-size: 1.1rem;
            color: #ffffff;
            font-weight: 600;
        }

        .text-muted {
            color: rgba(255, 255, 255, 0.3);
            font-size: 0.9rem;
        }

        .precio-total {
            text-align: center;
            font-family: 'Outfit', sans-serif;
            font-size: 1.05rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .text-success {
            color: #5de98a;
            font-size: 1.25rem;
            font-weight: 700;
            text-shadow: 0 0 10px rgba(93, 233, 138, 0.2);
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const asuntoSelect = document.getElementById("asunto");
            const infoBox = document.getElementById("precioInfoBox");
            const precioUsdDisplay = document.getElementById("precioUsdDisplay");
            const tasaDisplay = document.getElementById("tasaDisplay");
            const precioTotalBs = document.getElementById("precioTotalBs");
            const montoInput = document.getElementById("monto");

            // Tasa actual traída desde el backend
            const tasaActual = parseFloat("{{ $tasaActual }}");

            asuntoSelect.addEventListener("change", function () {
                const selectedOption = asuntoSelect.options[asuntoSelect.selectedIndex];
                const valorUsd = parseFloat(selectedOption.getAttribute("data-valor-usd")) || 0;

                if (valorUsd > 0 && tasaActual > 0) {
                    const totalBs = (valorUsd * tasaActual).toFixed(2);

                    // Actualizar textos en la tarjeta informativa
                    precioUsdDisplay.textContent = "$" + valorUsd.toFixed(2);
                    tasaDisplay.textContent = tasaActual.toFixed(2).replace('.', ',');
                    precioTotalBs.textContent = totalBs.replace('.', ',');

                    // Mostrar la tarjeta
                    infoBox.classList.remove("hidden");

                    // Rellenar automáticamente el input de "Monto" (El usuario puede borrarlo y modificarlo si quiere)
                    montoInput.value = totalBs;
                } else {
                    // Ocultar si selecciona una opción sin valor (o si no hay tasa registrada)
                    infoBox.classList.add("hidden");
                }
            });
        });
    </script>
</x-layout>