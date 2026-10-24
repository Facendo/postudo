<x-layout title='PostUDO || Aranceles'>
    <section class="login-page-container" style="width: 92%; max-width: 860px;">
        <div class="registration-form" style="max-width: 100%;">
            <h2 class="registration-form-title">Aranceles de Postgrado</h2>

            {{-- Formato que el sistema interpreta al mostrarlo en la portada.
                 El texto se pega crudo, igual que llega el comunicado. --}}
            <div class="formato-nota">
                <strong>Formato del texto</strong> (pégalo tal como llega el comunicado):
                <ul style="margin: 8px 0 0; padding-left: 18px;">
                    <li>Línea que empieza con ✅ → arancel; el precio va al final (p. ej. <code>$ 10,00</code> o <code>entre $ 30,00</code>).</li>
                    <li>Línea en MAYÚSCULAS → título del grupo (p. ej. <code>ESCOLARIDAD:</code> o <code>NUEVOS INGRESOS ✅</code>).</li>
                    <li>Línea corta sin punto, después de un grupo → también título (p. ej. «Otros aranceles de Escolaridad»).</li>
                    <li>Línea con «tasa de cambio» o «BCV» → aviso destacado del modal.</li>
                    <li>Líneas en blanco separan los párrafos de introducción.</li>
                </ul>
            </div>

            <form action="{{ route('administrador.aranceles.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="texto" class="form-label">Texto de la normativa</label>
                    <textarea id="texto" name="texto" class="form-input" rows="20"
                        style="width: 100%; min-height: 380px; line-height: 1.5;"
                        placeholder="Pega aquí el comunicado de aranceles…">{{ old('texto', $texto) }}</textarea>
                    @error('texto')
                        <span class="form-error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-actions" style="justify-content: center; gap: 14px;">
                    <button type="submit" class="submit-button">Guardar aranceles</button>
                    
                </div>
            </form>

            @if ($metadatos['actualizado_en'])
                <p class="meta-actualizacion">
                    Última actualización: {{ $metadatos['actualizado_en'] }}.
                </p>
            @else
                <p class="meta-actualizacion">
                    Sin actualizaciones: la portada muestra el texto por defecto de la normativa.
                </p>
            @endif
        </div>
    </section>

    <style>
        .formato-nota {
            background: rgba(247, 148, 29, .1);
            border: 1px solid rgba(247, 148, 29, .35);
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 20px;
            color: rgba(255, 255, 255, .85);
            font-size: .9rem;
            line-height: 1.6;
        }

        .formato-nota strong {
            color: #ffd9b8;
        }

        .formato-nota code {
            background: rgba(0, 0, 0, .25);
            padding: 1px 6px;
            border-radius: 5px;
            font-size: .85rem;
        }

        .enlace-portada {
            display: inline-block;
            padding: 12px 24px;
            border: 1px solid rgba(255, 255, 255, .25);
            border-radius: 30px;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: all .3s ease;
        }

        .enlace-portada:hover {
            border-color: var(--accent-orange);
            background: rgba(247, 148, 29, .12);
        }

        .meta-actualizacion {
            margin-top: 14px;
            text-align: center;
            color: rgba(255, 255, 255, .55);
            font-size: .85rem;
        }
    </style>
</x-layout>
