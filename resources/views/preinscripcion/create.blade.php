<x-layout title="PostUDO || Preinscripción a Postgrado">
    <style>
        /* Estilos locales del formulario de preinscripción (solo esta página) */
        .prein-form {
            width: 95%;
            max-width: 980px;
            margin: 30px auto;
            /* Texto en azul marino sobre el fondo claro del tema */
            color: var(--texto);
        }

        /* Etiquetas en azul marino (mismo tono que el resto del tema claro) */
        .prein-form .registration-form .form-label {
            color: var(--texto);
        }

        .prein-seccion {
            margin-bottom: 18px;
            padding: 18px 22px 22px;
        }

        .prein-seccion h3 {
            font-size: 1.05rem;
            font-weight: 600;
            margin-bottom: 14px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--borde);
        }

        .prein-seccion h3 i {
            margin-right: 8px;
            opacity: 0.85;
        }

        .prein-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 14px 20px;
        }

        .prein-ancho-completo {
            grid-column: 1 / -1;
        }

        .prein-opciones {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 26px;
            padding-top: 6px;
        }

        .prein-opciones label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .prein-opciones input {
            accent-color: #f7941d;
        }

        .prein-documentos {
            display: grid;
            gap: 10px;
        }

        .prein-documento {
            display: grid;
            grid-template-columns: 1fr auto auto;
            align-items: center;
            gap: 18px;
            padding: 8px 12px;
            border-radius: 8px;
            background: var(--fondo);
        }

        .prein-documento span {
            font-size: 0.93rem;
        }

        .prein-documento label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            font-size: 0.9rem;
        }

        /* Errores de validación: mensajes del servidor (directiva error de
           Blade) y de la capa JS en vivo, con el mismo estilo para ambos. */
        .error-message {
            display: block;
            color: #c0392b;
            font-size: 0.85rem;
            margin-top: 4px;
        }

        .form-input.is-invalid {
            border-color: #c0392b;
        }
    </style>

    <section class="prein-form">
        <div class="registration-form" style="max-width: none;">
            <h2 class="registration-form-title">Planilla de Preinscripción para el Postgrado</h2>

            <p style="color: var(--texto); text-align: center; margin-bottom: 25px; line-height: 1.6;">
                Completa todos los campos como aparecen en tu documento de identidad. Al enviar la
                planilla se guardará tu preinscripción y se descargará el archivo
                <strong>.docx</strong> relleno: imprímelo, fírmalo y preséntalo junto con la
                documentación en la Coordinación de Postgrado.
            </p>

            {{-- novalidate: la validación (JS en vivo + servidor) muestra los
                 errores con el estilo .error-message propio del formulario. --}}
            <form id="formPreinscripcion" action="{{ route('preinscripcion.store') }}" method="POST" novalidate>
                @csrf

                {{-- ============================================================ Voucher --}}
                <div class="prein-seccion">
                    <h3><i class="fa-solid fa-ticket"></i> Voucher del depósito</h3>
                    <div class="prein-grid">
                        <div class="form-group">
                            <label for="voucher_nro" class="form-label">Nº de voucher</label>
                            <input type="text" id="voucher_nro" name="voucher_nro" class="form-input"
                                value="{{ old('voucher_nro') }}" maxlength="50" inputmode="numeric">
                            @error('voucher_nro')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="voucher_fecha" class="form-label">Fecha del voucher</label>
                            <input type="date" id="voucher_fecha" name="voucher_fecha" class="form-input"
                                value="{{ old('voucher_fecha') }}" max="{{ date('Y-m-d') }}">
                            @error('voucher_fecha')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="voucher_banco" class="form-label">Banco</label>
                            <input type="text" id="voucher_banco" name="voucher_banco" class="form-input"
                                value="{{ old('voucher_banco') }}" maxlength="100">
                            @error('voucher_banco')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ================================================ Programa de interés --}}
                <div class="prein-seccion">
                    <h3><i class="fa-solid fa-graduation-cap"></i> Programa de interés</h3>

                    {{-- Aviso de la ventana de preinscripción (solo al llegar
                         desde el botón de un postgrado de la portada). --}}
                    @if ($ventanaPreinscripcion)
                        <p style="margin: 0 0 15px; padding: 10px 16px; border: 1px solid var(--borde); background: var(--superficie); border-radius: 8px; color: var(--texto);">
                            <i class="fa-solid fa-circle-info"></i>
                            Este postgrado está con la preinscripción abierta hasta el
                            <strong>{{ \Carbon\Carbon::parse($ventanaPreinscripcion)->format('d/m/Y') }}</strong>.
                        </p>
                    @endif
                    <div class="prein-grid">
                        <div class="form-group">
                            <label for="id_postgrado" class="form-label">Postgrado</label>
                            <select id="id_postgrado" name="id_postgrado" class="form-input form-select">
                                <option value="">Selecciona un Postgrado</option>
                                @foreach ($postgrados as $postgrado)
                                    <option value="{{ $postgrado->id_postgrado }}"
                                        {{ old('id_postgrado', $postgradoPreseleccionado) == $postgrado->id_postgrado ? 'selected' : '' }}>
                                        {{ $postgrado->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="codigo_mencion" class="form-label">Mención</label>
                            <select id="codigo_mencion" name="codigo_mencion" class="form-input form-select" required>
                                <option value="">Selecciona primero un Postgrado</option>
                            </select>
                            @error('codigo_mencion')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="sede" class="form-label">Sede</label>
                            <select id="sede" name="sede" class="form-input form-select" required>
                                <option value="">Selecciona una Sede</option>
                                @foreach ($sedes as $sede)
                                    <option value="{{ $sede }}" {{ old('sede') == $sede ? 'selected' : '' }}>
                                        {{ $sede }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sede')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- =============================================== A. Datos personales --}}
                <div class="prein-seccion">
                    <h3><i class="fa-solid fa-id-card"></i> A. Datos personales</h3>
                    <div class="prein-grid">
                        <div class="form-group">
                            <label for="apellidos" class="form-label">Apellidos</label>
                            <input type="text" id="apellidos" name="apellidos" class="form-input"
                                value="{{ old('apellidos') }}" maxlength="100" required>
                            @error('apellidos')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nombres" class="form-label">Nombres</label>
                            <input type="text" id="nombres" name="nombres" class="form-input"
                                value="{{ old('nombres') }}" maxlength="100" required>
                            @error('nombres')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="cedula" class="form-label">Cédula de Identidad</label>
                            <input type="text" id="cedula" name="cedula" class="form-input"
                                value="{{ old('cedula') }}" maxlength="20" required>
                            @error('cedula')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="edad" class="form-label">Edad</label>
                            <input type="number" id="edad" name="edad" class="form-input"
                                value="{{ old('edad') }}" min="18" max="99" required>
                            @error('edad')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="sexo" class="form-label">Sexo</label>
                            <select id="sexo" name="sexo" class="form-input form-select" required>
                                <option value="">Selecciona</option>
                                <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                            </select>
                            @error('sexo')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="estado_civil" class="form-label">Estado civil</label>
                            <select id="estado_civil" name="estado_civil" class="form-input form-select" required>
                                <option value="">Selecciona</option>
                                @foreach (['Soltero(a)', 'Casado(a)', 'Divorciado(a)', 'Viudo(a)', 'Unión libre'] as $estado)
                                    <option value="{{ $estado }}" {{ old('estado_civil') == $estado ? 'selected' : '' }}>
                                        {{ $estado }}
                                    </option>
                                @endforeach
                            </select>
                            @error('estado_civil')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nacionalidad" class="form-label">Nacionalidad</label>
                            <input type="text" id="nacionalidad" name="nacionalidad" class="form-input"
                                value="{{ old('nacionalidad', 'Venezolana') }}" maxlength="50" required>
                            @error('nacionalidad')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="lugar_nacimiento" class="form-label">Lugar y fecha de nacimiento</label>
                            <input type="text" id="lugar_nacimiento" name="lugar_nacimiento" class="form-input"
                                value="{{ old('lugar_nacimiento') }}" maxlength="150" required>
                            @error('lugar_nacimiento')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="etnia" class="form-label">Etnia</label>
                            <input type="text" id="etnia" name="etnia" class="form-input"
                                value="{{ old('etnia') }}" maxlength="50">
                            @error('etnia')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="direccion_permanente" class="form-label">Dirección permanente</label>
                            <input type="text" id="direccion_permanente" name="direccion_permanente" class="form-input"
                                value="{{ old('direccion_permanente') }}" maxlength="255" required>
                            @error('direccion_permanente')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" id="telefono" name="telefono" class="form-input"
                                value="{{ old('telefono') }}" maxlength="30" placeholder="0414-1234567" required>
                            @error('telefono')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="correo" class="form-label">Correo electrónico</label>
                            <input type="email" id="correo" name="correo" class="form-input"
                                value="{{ old('correo') }}" maxlength="150" required>
                            @error('correo')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group prein-ancho-completo">
                            <label for="direccion_otra" class="form-label">Otra dirección donde se pueda localizar</label>
                            <input type="text" id="direccion_otra" name="direccion_otra" class="form-input"
                                value="{{ old('direccion_otra') }}" maxlength="255">
                            @error('direccion_otra')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="telefono_otro" class="form-label">Teléfono (otra dirección)</label>
                            <input type="text" id="telefono_otro" name="telefono_otro" class="form-input"
                                value="{{ old('telefono_otro') }}" maxlength="30" placeholder="0414-1234567">
                            @error('telefono_otro')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="correo_otro" class="form-label">Correo (otra dirección)</label>
                            <input type="email" id="correo_otro" name="correo_otro" class="form-input"
                                value="{{ old('correo_otro') }}" maxlength="150">
                            @error('correo_otro')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="titulo_pregrado" class="form-label">Título de pregrado</label>
                            <input type="text" id="titulo_pregrado" name="titulo_pregrado" class="form-input"
                                value="{{ old('titulo_pregrado') }}" maxlength="150" required>
                            @error('titulo_pregrado')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="institucion_pregrado" class="form-label">Institución de pregrado</label>
                            <input type="text" id="institucion_pregrado" name="institucion_pregrado" class="form-input"
                                value="{{ old('institucion_pregrado') }}" maxlength="150" required>
                            @error('institucion_pregrado')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="anio_grado" class="form-label">Año de grado</label>
                            <input type="number" id="anio_grado" name="anio_grado" class="form-input"
                                value="{{ old('anio_grado') }}" min="1900" max="{{ date('Y') }}" required>
                            @error('anio_grado')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ================================================== B. Situación laboral --}}
                <div class="prein-seccion">
                    <h3><i class="fa-solid fa-briefcase"></i> B. Situación laboral</h3>
                    <div class="prein-grid">
                        <div class="form-group prein-ancho-completo">
                            <span class="form-label">¿Trabaja?</span>
                            <div class="prein-opciones">
                                @foreach (['dependiente' => 'Dependiente', 'cuenta_propia' => 'Cuenta propia', 'libre_ejercicio' => 'Libre ejercicio', 'no' => 'No'] as $valor => $etiqueta)
                                    <label>
                                        <input type="radio" name="trabaja" value="{{ $valor }}"
                                            {{ old('trabaja') == $valor ? 'checked' : '' }} required>
                                        {{ $etiqueta }}
                                    </label>
                                @endforeach
                            </div>
                            @error('trabaja')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group prein-campo-laboral">
                            <label for="ocupacion" class="form-label">Ocupación</label>
                            <input type="text" id="ocupacion" name="ocupacion" class="form-input"
                                value="{{ old('ocupacion') }}" maxlength="150">
                            @error('ocupacion')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group prein-campo-laboral">
                            <label for="cargo" class="form-label">Cargo</label>
                            <input type="text" id="cargo" name="cargo" class="form-input"
                                value="{{ old('cargo') }}" maxlength="150">
                            @error('cargo')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group prein-campo-laboral">
                            <label for="tiempo_cargo" class="form-label">Tiempo en el cargo</label>
                            <input type="text" id="tiempo_cargo" name="tiempo_cargo" class="form-input"
                                value="{{ old('tiempo_cargo') }}" maxlength="50">
                            @error('tiempo_cargo')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group prein-campo-laboral">
                            <label for="empresa" class="form-label">Empresa donde trabaja</label>
                            <input type="text" id="empresa" name="empresa" class="form-input"
                                value="{{ old('empresa') }}" maxlength="255">
                            @error('empresa')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group prein-ancho-completo prein-campo-laboral">
                            <label for="direccion_empresa" class="form-label">Dirección de la empresa</label>
                            <input type="text" id="direccion_empresa" name="direccion_empresa" class="form-input"
                                value="{{ old('direccion_empresa') }}" maxlength="255">
                            @error('direccion_empresa')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group prein-campo-laboral">
                            <label for="supervisor" class="form-label">Nombre del supervisor inmediato</label>
                            <input type="text" id="supervisor" name="supervisor" class="form-input"
                                value="{{ old('supervisor') }}" maxlength="150">
                            @error('supervisor')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group prein-campo-laboral">
                            <label for="telefono_supervisor" class="form-label">Teléfono del supervisor</label>
                            <input type="text" id="telefono_supervisor" name="telefono_supervisor" class="form-input"
                                value="{{ old('telefono_supervisor') }}" maxlength="30" placeholder="0414-1234567">
                            @error('telefono_supervisor')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group prein-campo-laboral">
                            <label for="correo_supervisor" class="form-label">Correo del supervisor</label>
                            <input type="email" id="correo_supervisor" name="correo_supervisor" class="form-input"
                                value="{{ old('correo_supervisor') }}" maxlength="150">
                            @error('correo_supervisor')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ================================================ Financiamiento y textos --}}
                <div class="prein-seccion">
                    <h3><i class="fa-solid fa-money-bill-wave"></i> Financiamiento y experiencia</h3>
                    <div class="prein-grid">
                        <div class="form-group prein-ancho-completo">
                            <span class="form-label">Fuente de financiamiento</span>
                            <div class="prein-opciones">
                                @foreach (['propia' => 'Propia', 'organismo_publico' => 'Organismo público', 'otra' => 'Otra'] as $valor => $etiqueta)
                                    <label>
                                        <input type="radio" name="fuente_financiamiento" value="{{ $valor }}"
                                            {{ old('fuente_financiamiento') == $valor ? 'checked' : '' }} required>
                                        {{ $etiqueta }}
                                    </label>
                                @endforeach
                            </div>
                            @error('fuente_financiamiento')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group prein-ancho-completo">
                            <label for="historial_trabajos" class="form-label">Historial de los tres (3) últimos trabajos</label>
                            <textarea id="historial_trabajos" name="historial_trabajos" class="form-input" rows="3"
                                maxlength="5000">{{ old('historial_trabajos') }}</textarea>
                            @error('historial_trabajos')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group prein-ancho-completo">
                            <label for="experiencia_laboral" class="form-label">Breve experiencia laboral-profesional</label>
                            <textarea id="experiencia_laboral" name="experiencia_laboral" class="form-input" rows="3"
                                maxlength="5000">{{ old('experiencia_laboral') }}</textarea>
                            @error('experiencia_laboral')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ========================================================= Grados --}}
                <div class="prein-seccion">
                    <h3><i class="fa-solid fa-file-lines"></i> Grados obtenidos</h3>
                    @foreach (['universitario' => 'Universitario', 'postgrado' => 'Postgrado', 'otros' => 'Otros'] as $tipo => $titulo)
                        <p style="margin: 12px 0 6px; font-weight: 600; font-size: 0.92rem;">{{ $titulo }}</p>
                        <div class="prein-grid">
                            <div class="form-group">
                                <label class="form-label">Institución</label>
                                <input type="text" name="grado_{{ $tipo }}_institucion" class="form-input"
                                    value="{{ old('grado_' . $tipo . '_institucion') }}" maxlength="150">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Título obtenido</label>
                                <input type="text" name="grado_{{ $tipo }}_titulo" class="form-input"
                                    value="{{ old('grado_' . $tipo . '_titulo') }}" maxlength="150">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Años cursados</label>
                                <input type="text" name="grado_{{ $tipo }}_anos" class="form-input"
                                    value="{{ old('grado_' . $tipo . '_anos') }}" maxlength="20" inputmode="numeric">
                            </div>
                            <div class="form-group">
                                <label class="form-label">País</label>
                                <input type="text" name="grado_{{ $tipo }}_pais" class="form-input"
                                    value="{{ old('grado_' . $tipo . '_pais') }}" maxlength="80">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Fecha de grado</label>
                                <input type="text" name="grado_{{ $tipo }}_fecha" class="form-input"
                                    value="{{ old('grado_' . $tipo . '_fecha') }}" maxlength="30" placeholder="dd-mm-aaaa">
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- ============================================== Referencias personales --}}
                <div class="prein-seccion">
                    <h3><i class="fa-solid fa-people-arrows"></i> C. Referencias personales</h3>
                    @foreach ([1 => 'Referencia 1', 2 => 'Referencia 2'] as $numero => $titulo)
                        <p style="margin: 12px 0 6px; font-weight: 600; font-size: 0.92rem;">{{ $titulo }}</p>
                        <div class="prein-grid">
                            @foreach ([
                                'nombre' => ['Nombre completo', 'text', 150, true],
                                'edad' => ['Edad', 'number', 3, true],
                                'direccion' => ['Dirección habitación', 'text', 255, true],
                                'telefono' => ['Teléfono', 'text', 30, true],
                                'email' => ['Correo electrónico', 'email', 150, true],
                                'ocupacion' => ['Ocupación', 'text', 150, true],
                                'cargo' => ['Cargo', 'text', 150, true],
                                'empresa' => ['Empresa donde trabaja', 'text', 150, true],
                                'telefono_trabajo' => ['Teléfono de la empresa', 'text', 30, false],
                                'email_trabajo' => ['Correo de la empresa', 'email', 150, false],
                                'direccion_trabajo' => ['Dirección de trabajo', 'text', 255, false],
                            ] as $campo => [$etiqueta, $tipo, $largo, $obligatorio])
                                <div class="form-group">
                                    <label class="form-label">{{ $etiqueta }}</label>
                                    <input type="{{ $tipo }}" name="ref{{ $numero }}_{{ $campo }}" class="form-input"
                                        value="{{ old('ref' . $numero . '_' . $campo) }}"
                                        maxlength="{{ $largo }}"
                                        @if ($tipo === 'number') min="1" max="110" @endif
                                        @if (in_array($campo, ['telefono', 'telefono_trabajo'])) placeholder="0414-1234567" @endif
                                        {{ $obligatorio ? 'required' : '' }}>
                                    @error('ref' . $numero . '_' . $campo)
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                {{-- ================================================ Documentación --}}
                <div class="prein-seccion">
                    <h3><i class="fa-solid fa-folder-open"></i> D. Documentación requerida</h3>
                    <div class="prein-documentos">
                        @foreach ([
                            'doc1' => 'Copia ampliada de la C.I. (ambas caras)',
                            'doc2' => 'Copia de la partida de nacimiento',
                            'doc3' => 'Copia de la libreta de inscripción militar (solo varones)',
                            'doc4' => 'Copia del título de pregrado',
                            'doc5' => 'Copia de notas certificadas de pregrado',
                            'doc6' => 'Síntesis curricular',
                            'doc7' => 'Constancia de trabajo',
                        ] as $nombre => $descripcion)
                            <div class="prein-documento">
                                <span>{{ $descripcion }}</span>
                                <label>
                                    <input type="radio" name="{{ $nombre }}" value="si"
                                        {{ old($nombre) == 'si' ? 'checked' : '' }} required> Sí
                                </label>
                                <label>
                                    <input type="radio" name="{{ $nombre }}" value="no"
                                        {{ old($nombre) == 'no' ? 'checked' : '' }}> No
                                </label>
                            </div>
                            @error($nombre)
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        @endforeach
                    </div>
                </div>

                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">
                        <i class="fa-solid fa-file-arrow-down"></i> Enviar y descargar planilla
                    </button>
                </div>
            </form>
        </div>
    </section>

    <script>
        // Datos de los postgrados con sus menciones (cascada postgrado -> mención).
        const postgrados = @json($postgrados);

        const postgradoSelect = document.getElementById('id_postgrado');
        const mencionSelect = document.getElementById('codigo_mencion');

        function poblarMenciones(idPostgrado) {
            mencionSelect.innerHTML = '<option value="">Selecciona una Mención</option>';

            const postgrado = postgrados.find(p => p.id_postgrado == idPostgrado);
            if (!postgrado || !postgrado.menciones || postgrado.menciones.length === 0) {
                return;
            }

            postgrado.menciones.forEach(mencion => {
                const opcion = document.createElement('option');
                opcion.value = mencion.id_mencion;
                opcion.textContent = mencion.nombre;
                mencionSelect.appendChild(opcion);
            });
        }

        postgradoSelect.addEventListener('change', function () {
            if (this.value) {
                poblarMenciones(this.value);
            } else {
                mencionSelect.innerHTML = '<option value="">Selecciona primero un Postgrado</option>';
            }
        });

        // Restaurar la selección previa si la validación del servidor falló
        // (o aplicar la precarga ?postgrado=COD de los botones de la portada,
        // que trae también la mención cuando es inequívoca).
        document.addEventListener('DOMContentLoaded', function () {
            const viejoPostgrado = @json(old('id_postgrado'));
            const viejaMencion = @json(old('codigo_mencion'));
            const preseleccionPostgrado = @json($postgradoPreseleccionado);
            const preseleccionMencion = @json($mencionPreseleccionada);
            const postgradoInicial = viejoPostgrado || preseleccionPostgrado;

            if (postgradoInicial) {
                postgradoSelect.value = postgradoInicial;
                poblarMenciones(postgradoInicial);

                // Lo que el visitante ya había intentado enviar manda sobre
                // la precarga; si no hay old(), vale la mención de la cohorte.
                const mencionInicial = viejaMencion || preseleccionMencion;
                if (mencionInicial) {
                    mencionSelect.value = mencionInicial;
                }
            }
        });
        // ============================================================
        // Capa de validación en vivo (espejo de las reglas del servidor).
        // El <form> lleva novalidate: aquí se muestran TODOS los mensajes
        // en español con el estilo .error-message de los mensajes del
        // servidor (directiva error de Blade).
        // ============================================================
        const formPreinscripcion = document.getElementById('formPreinscripcion');

        const TELEFONO_RE = /^(?:\+?58[\s.-]*)?0(?:4\d{2}|2\d{2})[\s.-]*\d{7}$/;
        const CEDULA_RE = /^(?:[VEPJG][\s.-]*)?\d+(?:[.\s-]\d+)*$/i;
        const FECHA_GRADO_RE = /^(0[1-9]|[12]\d|3[01])[-\/](0[1-9]|1[0-2])[-\/]\d{4}$/;
        const SOLO_LETRAS_RE = /^[a-zA-ZáéíóúüñÁÉÍÓÚÜÑ\s'-]+$/;
        const SOLO_NUMEROS_RE = /^\d+$/;

        function soloDigitos(texto) {
            return (texto || '').replace(/\D/g, '');
        }

        function normalizarTelefonoJS(texto) {
            let digitos = soloDigitos(texto);
            if (digitos.length > 11 && digitos.indexOf('58') === 0) {
                digitos = digitos.slice(2);
            }
            return digitos;
        }

        function hoyISO() {
            const hoy = new Date();
            const mes = String(hoy.getMonth() + 1).padStart(2, '0');
            const dia = String(hoy.getDate()).padStart(2, '0');
            return hoy.getFullYear() + '-' + mes + '-' + dia;
        }

        function mensajeCedula(valor) {
            if (!CEDULA_RE.test(valor)) {
                return 'El formato de la cédula no es válido (ejemplos: 30123456, V-30.123.456).';
            }
            const digitos = soloDigitos(valor.replace(/^[VEPJG]/i, ''));
            if (digitos.length < 5 || digitos.length > 10) {
                return 'La cédula debe tener entre 5 y 10 dígitos.';
            }
            return '';
        }

        function mensajeTelefono(valor) {
            if (TELEFONO_RE.test(valor)) {
                return '';
            }
            return 'El teléfono debe ser formato 0414-1234567 (también 0212-8765432 o +58-0414-1234567).';
        }

        function mensajeCorreo(valor) {
            if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valor)) {
                return '';
            }
            return 'El correo no es válido (ej.: nombre@dominio.com).';
        }

        function mensajeFechaGrado(valor) {
            if (!FECHA_GRADO_RE.test(valor)) {
                return 'La fecha de grado debe tener el formato dd-mm-aaaa (ej.: 15-07-2025).';
            }
            const partes = valor.replace(/\//g, '-').split('-').map(Number);
            const dia = partes[0];
            const mes = partes[1];
            const anio = partes[2];
            const fecha = new Date(anio, mes - 1, dia);
            if (fecha.getFullYear() !== anio || fecha.getMonth() !== mes - 1 || fecha.getDate() !== dia) {
                return 'La fecha de grado no es una fecha válida.';
            }
            if (anio > new Date().getFullYear()) {
                return 'La fecha de grado no puede ser futura.';
            }
            return '';
        }

        function mensajeSoloLetras(valor) {
            if (SOLO_LETRAS_RE.test(valor)) {
                return '';
            }
            return 'Solo se permiten letras, espacios, guiones y apóstrofos.';
        }

        function mensajeSoloNumeros(valor) {
            if (SOLO_NUMEROS_RE.test(valor)) {
                return '';
            }
            return 'Solo se permiten números.';
        }

        function refTelefonoDiferente(valor) {
            const propio = normalizarTelefonoJS(valor);
            const titular = normalizarTelefonoJS(document.getElementById('telefono').value);
            if (propio !== '' && propio === titular) {
                return 'El teléfono de la referencia no puede ser igual al teléfono del titular.';
            }
            return '';
        }

        function refCorreoDiferente(valor) {
            const propio = valor.trim().toLowerCase();
            const titular = document.getElementById('correo').value.trim().toLowerCase();
            if (propio !== '' && propio === titular) {
                return 'El correo de la referencia no puede ser igual al correo del titular.';
            }
            return '';
        }

        // Validadores por nombre de campo (solo se ejecutan con valor;
        // lo vacío lo resuelve la regla required genérica).
        const validadoresPorNombre = {
            cedula: mensajeCedula,
            nombres: mensajeSoloLetras,
            apellidos: mensajeSoloLetras,
            supervisor: mensajeSoloLetras,
            ref1_nombre: mensajeSoloLetras,
            ref2_nombre: mensajeSoloLetras,
            voucher_nro: mensajeSoloNumeros,
            grado_universitario_anos: mensajeSoloNumeros,
            grado_postgrado_anos: mensajeSoloNumeros,
            grado_otros_anos: mensajeSoloNumeros,
            grado_universitario_pais: mensajeSoloLetras,
            grado_postgrado_pais: mensajeSoloLetras,
            grado_otros_pais: mensajeSoloLetras,
            telefono: mensajeTelefono,
            telefono_otro: mensajeTelefono,
            telefono_supervisor: mensajeTelefono,
            ref1_telefono: function (valor) { return mensajeTelefono(valor) || refTelefonoDiferente(valor); },
            ref2_telefono: function (valor) { return mensajeTelefono(valor) || refTelefonoDiferente(valor); },
            ref1_telefono_trabajo: mensajeTelefono,
            ref2_telefono_trabajo: mensajeTelefono,
            ref1_email: function (valor) { return mensajeCorreo(valor) || refCorreoDiferente(valor); },
            ref2_email: function (valor) { return mensajeCorreo(valor) || refCorreoDiferente(valor); },
            ref1_email_trabajo: mensajeCorreo,
            ref2_email_trabajo: mensajeCorreo,
            correo_otro: mensajeCorreo,
            correo_supervisor: mensajeCorreo,
            voucher_fecha: function (valor) {
                return valor > hoyISO() ? 'La fecha del voucher no puede ser futura.' : '';
            },
            grado_universitario_fecha: mensajeFechaGrado,
            grado_postgrado_fecha: mensajeFechaGrado,
            grado_otros_fecha: mensajeFechaGrado,
        };

        function contenedorDe(campo) {
            return campo.closest('.form-group') || campo.closest('.prein-documento') || campo.parentElement;
        }

        function limpiarError(campo) {
            if (!campo) {
                return;
            }
            campo.classList.remove('is-invalid');
            const contenedor = contenedorDe(campo);
            if (contenedor) {
                contenedor.querySelectorAll(':scope > .error-message').forEach(nodo => nodo.remove());
            }
        }

        function mostrarError(campo, mensaje) {
            limpiarError(campo);
            campo.classList.add('is-invalid');
            const contenedor = contenedorDe(campo);
            if (contenedor) {
                const span = document.createElement('span');
                span.className = 'error-message';
                span.textContent = mensaje;
                contenedor.appendChild(span);
            }
        }

        function valorDe(campo) {
            if (campo.type === 'radio') {
                const marcado = formPreinscripcion.querySelector('input[name="' + campo.name + '"]:checked');
                return marcado ? marcado.value : '';
            }
            return (campo.value || '').trim();
        }

        function esRequerido(campo) {
            if (campo.type === 'radio') {
                const radios = formPreinscripcion.querySelectorAll('input[name="' + campo.name + '"]');
                for (let i = 0; i < radios.length; i++) {
                    if (radios[i].required && !radios[i].disabled) {
                        return true;
                    }
                }
                return false;
            }
            return campo.required;
        }

        function esVisible(campo) {
            if (campo.disabled) {
                return false;
            }
            // Los campos del bloque laboral oculto (display: none) no se
            // validan: offsetParent queda null cuando el ancestro se oculta.
            return campo.offsetParent !== null || campo.type === 'radio';
        }

        function mensajeDe(campo) {
            if (!esVisible(campo)) {
                return '';
            }

            const valor = valorDe(campo);

            if (!valor) {
                if (!esRequerido(campo)) {
                    return '';
                }
                if (campo.tagName === 'SELECT' || campo.type === 'radio') {
                    return 'Debe seleccionar una opción.';
                }
                return 'Este campo es obligatorio.';
            }

            if (campo.type === 'radio') {
                return '';
            }

            const validador = validadoresPorNombre[campo.name];
            if (validador) {
                const mensaje = validador(valor);
                if (mensaje) {
                    return mensaje;
                }
            }

            if (campo.type === 'number') {
                const min = campo.min !== '' ? Number(campo.min) : null;
                const max = campo.max !== '' ? Number(campo.max) : null;
                const numero = Number(valor);
                if (Number.isNaN(numero)) {
                    return 'Debe ser un número.';
                }
                if (min !== null && numero < min) {
                    return max !== null
                        ? 'Debe estar entre ' + min + ' y ' + max + '.'
                        : 'Debe ser ' + min + ' o más.';
                }
                if (max !== null && numero > max) {
                    return 'Debe estar entre ' + min + ' y ' + max + '.';
                }
            }

            if (campo.type === 'email') {
                return mensajeCorreo(valor);
            }

            return '';
        }

        function elementosValidables() {
            const vistos = {};
            const lista = [];
            formPreinscripcion.querySelectorAll('input, select, textarea').forEach(campo => {
                if (campo.type === 'hidden' || campo.name === '_token') {
                    return;
                }
                if (campo.type === 'radio') {
                    if (vistos[campo.name]) {
                        return;
                    }
                    vistos[campo.name] = true;
                }
                lista.push(campo);
            });
            return lista;
        }

        function validarCampo(campo) {
            const mensaje = mensajeDe(campo);
            if (mensaje) {
                mostrarError(campo, mensaje);
                return false;
            }
            limpiarError(campo);
            return true;
        }

        function validarTodo() {
            let primerError = null;
            elementosValidables().forEach(campo => {
                if (!validarCampo(campo) && !primerError) {
                    primerError = campo;
                }
            });
            return primerError;
        }

        function suscribirValidacion(campo) {
            if (campo.type === 'radio') {
                formPreinscripcion.querySelectorAll('input[name="' + campo.name + '"]').forEach(radio => {
                    radio.addEventListener('change', () => validarCampo(campo));
                });
            } else if (campo.tagName === 'SELECT' || campo.type === 'date' || campo.type === 'number') {
                campo.addEventListener('change', () => validarCampo(campo));
            } else {
                campo.addEventListener('blur', () => validarCampo(campo));
            }

            // Mientras tenga el error marcado, revalida en cada corrección.
            campo.addEventListener('input', () => {
                if (campo.classList.contains('is-invalid')) {
                    validarCampo(campo);
                }
            });
        }

        elementosValidables().forEach(suscribirValidacion);

        // Si el visitante corrige el teléfono o el correo del titular,
        // revalida también las referencias que no deben repetirlos.
        function suscribirRepeticionRef(nombreTitular, nombresRef) {
            const titular = formPreinscripcion.querySelector('[name="' + nombreTitular + '"]');
            if (!titular) {
                return;
            }
            titular.addEventListener('blur', function () {
                nombresRef.forEach(nombre => {
                    const referencia = formPreinscripcion.querySelector('[name="' + nombre + '"]');
                    if (referencia && referencia.value.trim()) {
                        validarCampo(referencia);
                    }
                });
            });
        }

        suscribirRepeticionRef('telefono', ['ref1_telefono', 'ref2_telefono']);
        suscribirRepeticionRef('correo', ['ref1_email', 'ref2_email']);

        // ---------------------------------------------------------
        // Bloque laboral: si trabaja = «no» se oculta y se limpia
        // (el servidor además fuerza esos campos a null); si trabaja,
        // ocupación, cargo y empresa pasan a ser obligatorios.
        // ---------------------------------------------------------
        const gruposLaborales = formPreinscripcion.querySelectorAll('.prein-campo-laboral');
        const laboralesObligatorios = ['ocupacion', 'cargo', 'empresa'];

        function actualizarBloqueLaboral() {
            const marcado = formPreinscripcion.querySelector('input[name="trabaja"]:checked');
            const trabaja = marcado ? marcado.value : null;
            const mostrar = trabaja !== null && trabaja !== 'no';

            gruposLaborales.forEach(grupo => {
                grupo.style.display = mostrar ? '' : 'none';
                grupo.querySelectorAll('input').forEach(campo => {
                    campo.required = mostrar && laboralesObligatorios.indexOf(campo.name) !== -1;
                    if (!mostrar) {
                        campo.value = '';
                        limpiarError(campo);
                    }
                });
            });
        }

        formPreinscripcion.querySelectorAll('input[name="trabaja"]').forEach(radio => {
            radio.addEventListener('change', actualizarBloqueLaboral);
        });

        // ---------------------------------------------------------
        // doc3 (libreta militar): solo varones. Con sexo F se marcan
        // los radios en «No», se deshabilitan y no se envían (el
        // servidor les pone «no» por defecto).
        // ---------------------------------------------------------
        const selectSexo = document.getElementById('sexo');

        function actualizarDoc3() {
            const esMujer = selectSexo.value === 'F';
            const radiosDoc3 = formPreinscripcion.querySelectorAll('input[name="doc3"]');
            const radioNo = formPreinscripcion.querySelector('input[name="doc3"][value="no"]');

            radiosDoc3.forEach(radio => {
                radio.disabled = esMujer;
                radio.required = !esMujer && radio.value === 'si';
            });

            if (esMujer && radioNo) {
                radioNo.checked = true;
                limpiarError(radioNo);
            }
        }

        selectSexo.addEventListener('change', actualizarDoc3);

        // Envío: con errores se bloquea, se resalta el primero y se
        // hace scroll hasta él; sin errores el servidor revalida todo.
        formPreinscripcion.addEventListener('submit', function (evento) {
            const primerError = validarTodo();
            if (primerError) {
                evento.preventDefault();
                primerError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                primerError.focus({ preventScroll: true });
            }
        });

        // Estado inicial (tras restaurar old()): bloque laboral y doc3.
        document.addEventListener('DOMContentLoaded', function () {
            actualizarBloqueLaboral();
            actualizarDoc3();
        });
    </script>
</x-layout>
