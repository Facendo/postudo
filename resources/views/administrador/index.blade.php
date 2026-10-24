<x-layout title='PostUDO || Administrador - Inicio'>

    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <div class="admin-dashboard-container">
        <div class="admin-header">
            <h2 class="admin-title">¡Bienvenido!</h2>
            <p class="admin-subtitle">Gestione todas las funciones del sistema desde aquí.</p>
        </div>

        <div class="admin-grid">
            <a href="{{ route('asuntos.index') }}" class="admin-card">
                <i class="fa-solid fa-file-alt"></i>
                <span>Aranceles</span>
            </a>
            <a href="{{ route('pago.controlpagos') }}" class="admin-card">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                <span>Pagos</span>
            </a>
            <a href="{{ route('administrador.finanzas') }}" class="admin-card">
                <i class="fa-solid fa-scale-balanced"></i>
                <span>Ingresos y Egresos</span>
            </a>
            <a href="{{ route('pago.actualizados') }}" class="admin-card">
                <i class="fa-solid fa-circle-check"></i>
                <span>Pagos Actualizados</span>
            </a>
            <a href="{{ route('pago.pendientes') }}" class="admin-card">
                <i class="fa-solid fa-clock"></i>
                <span>Pagos Pendientes</span>
            </a>
            <a href="{{ route('administrador.coordinador.create') }}" class="admin-card">
                <i class="fa-solid fa-user-tie"></i>
                <span>Registrar Coordinador</span>
            </a>
            <a href="{{ route('administrador.aranceles.edit') }}" class="admin-card">
                <i class="fa-solid fa-money-bill-trend-up"></i>
                <span>Gaceta de Aranceles</span>
            </a>
        </div>
    </div>

    {{-- ===== WIDGET TASA DE CAMBIO (esquina inferior derecha) ===== --}}
    <div class="tasa-cambio-widget" id="tasaCambioWidget">
        <div class="tasa-cambio-header">
            <i class="fa-solid fa-dollar-sign tasa-icon"></i>
            <div>
                <span class="tasa-label">Tasa de Cambio</span>
                <span class="tasa-fecha" id="tasaFecha">
                    @if(isset($tasaCambio['fecha']))
                        {{ $tasaCambio['fecha'] }} · {{ $tasaCambio['hora'] }} · Fuente:
                        {{ ($tasaCambio['fuente'] ?? 'manual') === 'BCV' ? 'BCV' : 'Manual' }}
                    @else
                        Sin registrar
                    @endif
                </span>
            </div>
        </div>

        <div class="tasa-valor-display" id="tasaValorDisplay">
            @if(isset($tasaCambio['valor']))
                <span class="tasa-moneda">Bs.</span>
                <span class="tasa-numero"
                    id="tasaNumeroActual">{{ number_format($tasaCambio['valor'], 2, ',', '.') }}</span>
                <span class="tasa-por">&nbsp;/ $</span>
            @else
                <span class="tasa-sin-valor">Sin valor registrado</span>
            @endif
        </div>

        <form id="tasaCambioForm" class="tasa-cambio-form">
            @csrf
            <div class="tasa-input-group">
                <span class="tasa-prefix">Bs.</span>
                <input type="number" id="tasaInput" name="tasa" class="tasa-input" placeholder="0.00" step="0.01"
                    min="0" value="{{ $tasaCambio['valor'] ?? '' }}" required />
                <span class="tasa-suffix">/ $</span>
            </div>
            <button type="submit" class="tasa-btn-guardar" id="tasaBtnGuardar" title="Guardar tasa de cambio">
                <i class="fa-solid fa-check" id="tasaBtnIcon"></i>
                <span id="tasaBtnTexto">Actualizar</span>
            </button>
            <button type="button" class="tasa-btn-bcv" id="tasaBtnBcv" title="Obtener la tasa oficial publicada por el BCV">
                <i class="fa-solid fa-rotate" id="tasaBtnBcvIcon"></i>
                <span id="tasaBtnBcvTexto">Sincronizar con BCV</span>
            </button>
        </form>

        <div class="tasa-feedback" id="tasaFeedback"></div>
    </div>

    <style>
        /* ===== WIDGET TASA DE CAMBIO ===== */
        .tasa-cambio-widget {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 280px;
            background: rgba(0, 10, 32, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(247, 148, 29, 0.25);
            border-radius: 20px;
            padding: 20px 22px;
            z-index: 999;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.05);
            animation: slideInWidget 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        @keyframes slideInWidget {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .tasa-cambio-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .tasa-icon {
            font-size: 1.4rem;
            color: #f7941d;
            background: rgba(247, 148, 29, 0.12);
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .tasa-label {
            display: block;
            font-family: 'Outfit', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            color: #ffffff;
            letter-spacing: 0.3px;
            line-height: 1.2;
        }

        .tasa-fecha {
            display: block;
            font-family: 'Outfit', sans-serif;
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.4);
            margin-top: 2px;
        }

        /* Valor actual grande */
        .tasa-valor-display {
            display: flex;
            align-items: baseline;
            gap: 3px;
            margin-bottom: 16px;
            min-height: 36px;
        }

        .tasa-moneda {
            font-family: 'Outfit', sans-serif;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.5);
            font-weight: 400;
        }

        .tasa-numero {
            font-family: 'Outfit', sans-serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1;
        }

        .tasa-por {
            font-family: 'Outfit', sans-serif;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.5);
        }

        .tasa-sin-valor {
            font-family: 'Outfit', sans-serif;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.3);
            font-style: italic;
        }

        /* Formulario */
        .tasa-cambio-form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .tasa-input-group {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            overflow: hidden;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .tasa-input-group:focus-within {
            border-color: rgba(247, 148, 29, 0.5);
            box-shadow: 0 0 0 3px rgba(247, 148, 29, 0.12);
        }

        .tasa-prefix,
        .tasa-suffix {
            font-family: 'Outfit', sans-serif;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.4);
            padding: 0 8px;
            white-space: nowrap;
            user-select: none;
        }

        .tasa-input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            color: #ffffff;
            font-family: 'Outfit', sans-serif;
            font-size: 1rem;
            font-weight: 500;
            padding: 10px 4px;
            width: 100%;
            -moz-appearance: textfield;
        }

        .tasa-input::-webkit-outer-spin-button,
        .tasa-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .tasa-input::placeholder {
            color: rgba(255, 255, 255, 0.2);
        }

        .tasa-btn-guardar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            background: linear-gradient(135deg, #003da5, #001a4d);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 10px 16px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 4px 15px rgba(0, 61, 165, 0.3);
            letter-spacing: 0.2px;
        }

        .tasa-btn-guardar:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 61, 165, 0.45);
        }

        .tasa-btn-guardar:active {
            transform: translateY(0);
        }

        .tasa-btn-guardar.loading {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .tasa-btn-guardar.success {
            background: linear-gradient(135deg, #1a7a3a, #28a745);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.35);
        }

        /* Botón secundario: sincronizar con BCV */
        .tasa-btn-bcv {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            background: rgba(255, 255, 255, 0.06);
            color: rgba(255, 255, 255, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 12px;
            padding: 9px 16px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.82rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .tasa-btn-bcv:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
        }

        .tasa-btn-bcv:active {
            transform: translateY(0);
        }

        .tasa-btn-bcv.loading {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .tasa-btn-bcv.success {
            background: rgba(40, 167, 69, 0.15);
            border-color: rgba(40, 167, 69, 0.5);
            color: #5de98a;
        }

        /* Feedback */
        .tasa-feedback {
            font-family: 'Outfit', sans-serif;
            font-size: 0.75rem;
            text-align: center;
            min-height: 16px;
            margin-top: 4px;
            transition: opacity 0.3s ease;
        }

        .tasa-feedback.ok {
            color: #5de98a;
        }

        .tasa-feedback.err {
            color: #ff6b6b;
        }
    </style>

    <script>
        (function () {
            const form = document.getElementById('tasaCambioForm');
            const input = document.getElementById('tasaInput');
            const feedback = document.getElementById('tasaFeedback');
            const btnIcon = document.getElementById('tasaBtnIcon');
            const btnTexto = document.getElementById('tasaBtnTexto');
            const btn = document.getElementById('tasaBtnGuardar');
            const btnBcv = document.getElementById('tasaBtnBcv');
            const btnBcvIcon = document.getElementById('tasaBtnBcvIcon');
            const btnBcvTexto = document.getElementById('tasaBtnBcvTexto');
            const tasaFecha = document.getElementById('tasaFecha');
            const tasaDisplay = document.getElementById('tasaValorDisplay');
            const token = document.querySelector('input[name="_token"]').value;

            // Actualiza el display del valor y la línea de fecha/fuente sin recargar
            function actualizarDisplay(tasa) {
                const v = parseFloat(tasa.valor);
                const fuente = tasa.fuente === 'BCV' ? 'BCV' : 'Manual';
                tasaDisplay.innerHTML = `
                    <span class="tasa-moneda">Bs.</span>
                    <span class="tasa-numero" id="tasaNumeroActual">${v.toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</span>
                    <span class="tasa-por">&nbsp;/ $</span>
                `;
                tasaFecha.textContent = `${tasa.fecha} · ${tasa.hora} · Fuente: ${fuente}`;
                input.value = v;
            }

            form.addEventListener('submit', async function (e) {
                e.preventDefault();

                const valor = parseFloat(input.value);
                if (isNaN(valor) || valor <= 0) {
                    mostrarFeedback('Ingresa un valor mayor a 0.', 'err');
                    return;
                }

                // Estado cargando
                btn.classList.add('loading');
                btn.disabled = true;
                btnIcon.className = 'fa-solid fa-spinner fa-spin';
                btnTexto.textContent = 'Guardando…';
                feedback.className = 'tasa-feedback';
                feedback.textContent = '';

                try {
                    const res = await fetch("{{ route('administrador.tasa_cambio.save') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ tasa: valor }),
                    });

                    if (!res.ok) throw new Error('Error del servidor');

                    const data = await res.json();

                    if (data.success) {
                        actualizarDisplay(data.tasa);

                        btn.classList.remove('loading');
                        btn.classList.add('success');
                        btnIcon.className = 'fa-solid fa-check';
                        btnTexto.textContent = 'Guardado';
                        mostrarFeedback('¡Tasa guardada manualmente! Úsala solo si el BCV no responde.', 'ok');

                        setTimeout(() => {
                            btn.classList.remove('success');
                            btnTexto.textContent = 'Actualizar';
                            btn.disabled = false;
                        }, 2500);
                    }
                } catch (err) {
                    btn.classList.remove('loading');
                    btn.disabled = false;
                    btnIcon.className = 'fa-solid fa-xmark';
                    btnTexto.textContent = 'Actualizar';
                    mostrarFeedback('Error al guardar. Intenta de nuevo.', 'err');
                }
            });

            // Botón: sincronizar con la tasa oficial publicada por el BCV
            btnBcv.addEventListener('click', async function () {
                btnBcv.classList.add('loading');
                btnBcv.disabled = true;
                btnBcvIcon.className = 'fa-solid fa-spinner fa-spin';
                btnBcvTexto.textContent = 'Sincronizando…';
                feedback.className = 'tasa-feedback';
                feedback.textContent = '';

                try {
                    const res = await fetch("{{ route('administrador.tasa_cambio.bcv') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                        },
                    });

                    const data = await res.json().catch(() => null);

                    if (!res.ok || !data || !data.success) {
                        throw new Error(data && data.message ? data.message : 'La API del BCV no respondió.');
                    }

                    actualizarDisplay(data.tasa);

                    btnBcv.classList.remove('loading');
                    btnBcv.classList.add('success');
                    btnBcvIcon.className = 'fa-solid fa-check';
                    btnBcvTexto.textContent = 'Sincronizado';
                    mostrarFeedback(`¡Tasa del BCV vigente para el ${data.tasa.fecha} sincronizada!`, 'ok');

                    setTimeout(() => {
                        btnBcv.classList.remove('success');
                        btnBcvIcon.className = 'fa-solid fa-rotate';
                        btnBcvTexto.textContent = 'Sincronizar con BCV';
                        btnBcv.disabled = false;
                    }, 2500);
                } catch (err) {
                    btnBcv.classList.remove('loading');
                    btnBcv.disabled = false;
                    btnBcvTexto.textContent = 'Sincronizar con BCV';
                    mostrarFeedback(err.message || 'Error al sincronizar con el BCV. Puedes intentar de nuevo.', 'err');

                    setTimeout(() => { btnBcvIcon.className = 'fa-solid fa-rotate'; }, 2500);
                }
            });

            function mostrarFeedback(msg, tipo) {
                feedback.textContent = msg;
                feedback.className = `tasa-feedback ${tipo}`;
                setTimeout(() => { feedback.textContent = ''; feedback.className = 'tasa-feedback'; }, 5000);
            }
        })();
    </script>

</x-layout>