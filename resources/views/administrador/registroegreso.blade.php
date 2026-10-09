<x-layout title='PostUDO || Registrar Egreso'>
    <section class="login-page-container">
        <div class="registration-form" style="max-width: 560px;">
            <h2 class="registration-form-title">Registrar Egreso</h2>

            {{-- Tasa de cambio vigente (informativo) --}}
            @if ($tasaCambio && isset($tasaCambio['valor']))
                <div class="egreso-tasa-badge">
                    <i class="fa-solid fa-dollar-sign"></i>
                    Tasa vigente: <strong>Bs. {{ number_format($tasaCambio['valor'], 2, ',', '.') }} / $</strong>
                    <span>({{ $tasaCambio['fecha'] ?? '' }} · {{ $tasaCambio['hora'] ?? '' }})</span>
                </div>
            @else
                <div class="egreso-tasa-badge egreso-tasa-badge--warn">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Sin tasa de cambio registrada.
                </div>
            @endif

            <form action="{{ route('administrador.egreso.store') }}" method="POST">
                @csrf

                {{-- ===== MONTO ===== --}}
                <div class="form-group">
                    <label for="monto" class="form-label">
                        <i class="fa-solid fa-coins" style="color:#ff4d4d; margin-right:6px;"></i>
                        Monto del Egreso (Bs.)
                    </label>
                    <div class="egreso-monto-wrapper">
                        <span class="egreso-monto-prefix">Bs.</span>
                        <input type="number" id="monto" name="monto" class="form-input egreso-monto-input"
                            placeholder="0.00" step="0.01" min="0.01" value="{{ old('monto') }}" required>
                    </div>
                    @error('monto')
                        <small class="egreso-error">{{ $message }}</small>
                    @enderror
                </div>

                {{-- ===== FECHA ===== --}}
                <div class="form-group">
                    <label for="fecha" class="form-label">
                        <i class="fa-solid fa-calendar-days" style="color:#ff4d4d; margin-right:6px;"></i>
                        Fecha del Egreso
                    </label>
                    <input type="date" id="fecha" name="fecha" class="form-input"
                        value="{{ old('fecha', date('Y-m-d')) }}" required>
                    @error('fecha')
                        <small class="egreso-error">{{ $message }}</small>
                    @enderror
                </div>

                {{-- ===== MOTIVO ===== --}}
                <div class="form-group">
                    <label for="motivo" class="form-label">
                        <i class="fa-solid fa-tag" style="color:#ff4d4d; margin-right:6px;"></i>
                        Motivo
                    </label>
                    <select id="motivo" name="motivo" class="form-input" required>
                        <option value="" disabled {{ old('motivo') ? '' : 'selected' }}>Seleccione un motivo…</option>
                        @foreach ($motivos as $motivo)
                            <option value="{{ $motivo }}" {{ old('motivo') === $motivo ? 'selected' : '' }}>
                                {{ $motivo }}
                            </option>
                        @endforeach
                    </select>
                    @error('motivo')
                        <small class="egreso-error">{{ $message }}</small>
                    @enderror
                </div>

                {{-- ===== DETALLE (visible si motivo es "Otro") ===== --}}
                <div class="form-group" id="grupo-detalle" style="display: none;">
                    <label for="detalle" class="form-label">
                        <i class="fa-solid fa-pen" style="color:#ff4d4d; margin-right:6px;"></i>
                        Detalle del Motivo
                    </label>
                    <input type="text" id="detalle" name="detalle" class="form-input"
                        placeholder="Describa el motivo del egreso" value="{{ old('detalle') }}" maxlength="255">
                    <small class="egreso-hint">Obligatorio cuando el motivo es "Otro".</small>
                    @error('detalle')
                        <small class="egreso-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">
                        <i class="fa-solid fa-plus" style="margin-right:8px;"></i>
                        Registrar Egreso
                    </button>
                </div>
            </form>
        </div>
    </section>

    <style>
        .egreso-tasa-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 8px;
            background: rgba(255, 77, 77, 0.1);
            border: 1px solid rgba(255, 77, 77, 0.25);
            border-radius: 50px;
            padding: 8px 20px;
            margin-bottom: 24px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.75);
        }

        .egreso-tasa-badge i {
            color: #ff4d4d;
        }

        .egreso-tasa-badge strong {
            color: #ffffff;
        }

        .egreso-tasa-badge span {
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.78rem;
        }

        .egreso-tasa-badge--warn {
            background: rgba(255, 193, 7, 0.1);
            border-color: rgba(255, 193, 7, 0.3);
        }

        .egreso-tasa-badge--warn i {
            color: #ffc107;
        }

        .egreso-monto-wrapper {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            overflow: hidden;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .egreso-monto-wrapper:focus-within {
            border-color: #ff4d4d;
            box-shadow: 0 0 0 3px rgba(255, 77, 77, 0.2);
        }

        .egreso-monto-prefix {
            padding: 0 14px;
            font-family: 'Outfit', sans-serif;
            font-size: 1.05rem;
            font-weight: 600;
            color: #ff4d4d;
            background: rgba(255, 77, 77, 0.08);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            align-self: stretch;
            display: flex;
            align-items: center;
        }

        .egreso-monto-input {
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            background: transparent !important;
            -moz-appearance: textfield;
        }

        .egreso-monto-input::-webkit-outer-spin-button,
        .egreso-monto-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .egreso-hint {
            display: block;
            margin-top: 6px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.35);
            line-height: 1.4;
        }

        .egreso-error {
            display: block;
            margin-top: 6px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.78rem;
            color: #ff6b6b;
        }
    </style>

    <script>
        (function () {
            const motivoSelect = document.getElementById('motivo');
            const grupoDetalle = document.getElementById('grupo-detalle');

            function alternarDetalle() {
                grupoDetalle.style.display = motivoSelect.value === 'Otro' ? 'block' : 'none';
            }

            motivoSelect.addEventListener('change', alternarDetalle);
            alternarDetalle(); // Estado inicial (por si hay old() con "Otro")
        })();
    </script>
</x-layout>
