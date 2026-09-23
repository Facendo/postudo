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
                <span>Asuntos</span>
            </a>
            <a href="{{ route('pago.controlpagos') }}" class="admin-card">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                <span>Pagos</span>
            </a>
            <a href="{{ route('registro_estudiante.index') }}" class="admin-card">
                <i class="fa-solid fa-user-plus"></i>
                <span>Registrar Estudiante</span>
            </a>
            <a href="{{ route('pago.actualizados') }}" class="admin-card">
                <i class="fa-solid fa-circle-check"></i>
                <span>Pagos Actualizados</span>
            </a>
            <a href="{{ route('pago.pendientes') }}" class="admin-card">
                <i class="fa-solid fa-clock"></i>
                <span>Pagos Pendientes</span>
            </a>
            <a href="{{ route('administrador.especialidades.index') }}" class="admin-card">
                <i class="fa-solid fa-graduation-cap"></i>
                <span>Especialidades</span>
            </a>
            <a href="{{ route('administrador.prueba_especialidades') }}" class="admin-card">
                <i class="fa-solid fa-users-viewfinder"></i>
                <span>Estudiantes</span>
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
                    {{ isset($tasaCambio['fecha']) ? $tasaCambio['fecha'] . ' · ' . $tasaCambio['hora'] : 'Sin registrar' }}
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
            background: rgba(15, 5, 5, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 77, 77, 0.25);
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
            color: #ff4d4d;
            background: rgba(255, 77, 77, 0.12);
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
            border-color: rgba(255, 77, 77, 0.5);
            box-shadow: 0 0 0 3px rgba(255, 77, 77, 0.12);
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
            background: linear-gradient(135deg, #cc0000, #ff4d4d);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 10px 16px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 4px 15px rgba(204, 0, 0, 0.3);
            letter-spacing: 0.2px;
        }

        .tasa-btn-guardar:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(204, 0, 0, 0.45);
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
            const tasaFecha = document.getElementById('tasaFecha');
            const tasaDisplay = document.getElementById('tasaValorDisplay');

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
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ tasa: valor }),
                    });

                    if (!res.ok) throw new Error('Error del servidor');

                    const data = await res.json();

                    if (data.success) {
                        // Actualizar display sin recargar
                        const v = parseFloat(data.tasa.valor);
                        tasaDisplay.innerHTML = `
                            <span class="tasa-moneda">Bs.</span>
                            <span class="tasa-numero" id="tasaNumeroActual">${v.toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</span>
                            <span class="tasa-por">&nbsp;/ $</span>
                        `;
                        tasaFecha.textContent = `${data.tasa.fecha} · ${data.tasa.hora}`;

                        btn.classList.remove('loading');
                        btn.classList.add('success');
                        btnIcon.className = 'fa-solid fa-check';
                        btnTexto.textContent = 'Guardado';
                        mostrarFeedback('¡Tasa actualizada correctamente!', 'ok');

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

            function mostrarFeedback(msg, tipo) {
                feedback.textContent = msg;
                feedback.className = `tasa-feedback ${tipo}`;
                setTimeout(() => { feedback.textContent = ''; feedback.className = 'tasa-feedback'; }, 3500);
            }
        })();
    </script>

</x-layout>