<x-layout title='PostUDO || Registrar Asunto'>
    <section class="login-page-container">
        <div class="registration-form" style="max-width: 560px;">
            <h2 class="registration-form-title">Registrar Asunto</h2>

            <form action="{{ route('asuntos.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre del Asunto</label>
                    <input type="text" id="nombre" name="nombre" class="form-input"
                        placeholder="Ej: Inscripción de Materias" value="{{ old('nombre') }}" required>
                </div>

                <div class="form-group">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea id="descripcion" name="descripcion" class="form-input"
                        placeholder="Ej: Proceso de inscripción de asignaturas para el nuevo periodo."
                        rows="3">{{ old('descripcion') }}</textarea>
                </div>

                {{-- ===== CAMPO VALOR EN USD ===== --}}
                <div class="form-group">
                    <label for="valor_usd" class="form-label">
                        <i class="fa-solid fa-dollar-sign" style="color:#ff4d4d; margin-right:6px;"></i>
                        Valor del Asunto (USD)
                    </label>
                    <div class="valor-usd-wrapper">
                        <span class="valor-usd-prefix">$</span>
                        <input type="number" id="valor_usd" name="valor_usd" class="form-input valor-usd-input"
                            placeholder="0.00" step="0.01" min="0" value="{{ old('valor_usd', 0) }}">
                    </div>
                    <small class="valor-usd-hint">
                        Este valor se multiplicará por la tasa de cambio vigente para calcular el monto en Bs.
                    </small>
                </div>

                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">
                        <i class="fa-solid fa-plus" style="margin-right:8px;"></i>
                        Registrar Asunto
                    </button>
                </div>
            </form>
        </div>
    </section>

    <style>
        .valor-usd-wrapper {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            overflow: hidden;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .valor-usd-wrapper:focus-within {
            border-color: #ff4d4d;
            box-shadow: 0 0 0 3px rgba(255, 77, 77, 0.2);
        }

        .valor-usd-prefix {
            padding: 0 14px;
            font-family: 'Outfit', sans-serif;
            font-size: 1.1rem;
            font-weight: 600;
            color: #ff4d4d;
            background: rgba(255, 77, 77, 0.08);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            align-self: stretch;
            display: flex;
            align-items: center;
        }

        .valor-usd-input {
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            background: transparent !important;
            -moz-appearance: textfield;
        }

        .valor-usd-input::-webkit-outer-spin-button,
        .valor-usd-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .valor-usd-hint {
            display: block;
            margin-top: 6px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.35);
            line-height: 1.4;
        }
    </style>
</x-layout>