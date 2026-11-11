<x-layout title='PostUDO || Editar Cohorte'>
    <section class="login-page-container">
        <div class="registration-form">
            <h2 class="registration-form-title">Editar Cohorte</h2>

            <form action="{{ route('administrador.gestioncohorte.update', $cohorte->codigo_cohorte) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="codigo_cohorte" class="form-label">Código de Cohorte</label>
                    <input type="text" id="codigo_cohorte" name="codigo_cohorte" class="form-input" placeholder="Ej: C-2025-INF-01" value="{{ old('codigo_cohorte', $cohorte->codigo_cohorte) }}" required>
                    @error('codigo_cohorte')
                        <p class="form-error-message">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mención (área -> postgrado -> mención): obligatoria. El postgrado
                     no se envía: el controlador lo deriva de la mención elegida. --}}
                <div class="form-group">
                    <label for="codigo_mencion" class="form-label">Mención</label>
                    <select id="codigo_mencion" name="codigo_mencion" class="form-input" required>
                        <option value="" disabled {{ old('codigo_mencion', $cohorte->codigo_mencion) ? '' : 'selected' }}>Selecciona una mención</option>
                        @foreach ($menciones->groupBy('id_postgrado') as $grupo)
                            @php $postgradoGrupo = $grupo->first()->postgrado; @endphp
                            <optgroup label="{{ $postgradoGrupo->nombre ?? 'Postgrado' }}">
                                @foreach ($grupo as $mencion)
                                    <option value="{{ $mencion->id_mencion }}" {{ (string) old('codigo_mencion', $cohorte->codigo_mencion) === (string) $mencion->id_mencion ? 'selected' : '' }}>
                                        {{ $mencion->nombre }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('codigo_mencion')
                        <p class="form-error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-input" value="{{ old('fecha_inicio', $cohorte->fecha_inicio) }}" required>
                    @error('fecha_inicio')
                        <p class="form-error-message">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Duración fija: la fecha de fin se recalcula (fecha_inicio + 4 años)
                     y NO se envía al servidor; el controlador la calcula igual. --}}
                @php
                    // try/catch: si old('fecha_inicio') vino inválido tras un error de
                    // validación, Carbon::parse lanzaría y rompería la página.
                    $finCalculada = '';
                    try {
                        $finCalculada = \Carbon\Carbon::parse(old('fecha_inicio', $cohorte->fecha_inicio))
                            ->addYears(4)->toDateString();
                    } catch (\Exception $e) {
                        $finCalculada = '';
                    }
                @endphp
                <div class="form-group">
                    <label for="fecha_fin" class="form-label">Fecha de Fin (calculada: +4 años)</label>
                    <input type="date" id="fecha_fin" class="form-input" value="{{ $finCalculada }}" readonly tabindex="-1" style="background-color: rgba(255,255,255,0.15); cursor: not-allowed;">
                </div>

                <div class="form-group">
                    <label for="nro_de_cohorte" class="form-label">Número de Cohorte</label>
                    <input type="number" id="nro_de_cohorte" name="nro_de_cohorte" class="form-input" placeholder="Ej: 1" min="1" value="{{ old('nro_de_cohorte', $cohorte->nro_de_cohorte) }}" required>
                    @error('nro_de_cohorte')
                        <p class="form-error-message">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ventana de preinscripción (obligatoria): es lo que habilita
                     el postgrado en la portada. Aquí es donde se dan fechas a
                     las cohortes antiguas que nacieron sin ventana. --}}
                <div class="form-group">
                    <label for="preinscripcion_desde" class="form-label">Preinscripción: desde</label>
                    <input type="date" id="preinscripcion_desde" name="preinscripcion_desde" class="form-input" value="{{ old('preinscripcion_desde', $cohorte->preinscripcion_desde) }}" required>
                    @error('preinscripcion_desde')
                        <p class="form-error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="preinscripcion_hasta" class="form-label">Preinscripción: hasta</label>
                    <input type="date" id="preinscripcion_hasta" name="preinscripcion_hasta" class="form-input" value="{{ old('preinscripcion_hasta', $cohorte->preinscripcion_hasta) }}" required>
                    @error('preinscripcion_hasta')
                        <p class="form-error-message">{{ $message }}</p>
                    @enderror
                    <small style="color: var(--texto); opacity: 0.75;">
                        Mientras hoy esté dentro de este periodo, el postgrado mostrará su botón de preinscripción en la portada.
                    </small>
                </div>

                {{-- Ventana de inscripción (opcional): se registra desde ya
                     pero aún no se usa — la inscripción del estudiante queda
                     como está hasta la fase 2. --}}
                <div class="form-group">
                    <label for="inscripcion_desde" class="form-label">Inscripción: desde (opcional)</label>
                    <input type="date" id="inscripcion_desde" name="inscripcion_desde" class="form-input" value="{{ old('inscripcion_desde', $cohorte->inscripcion_desde) }}">
                    @error('inscripcion_desde')
                        <p class="form-error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="inscripcion_hasta" class="form-label">Inscripción: hasta (opcional)</label>
                    <input type="date" id="inscripcion_hasta" name="inscripcion_hasta" class="form-input" value="{{ old('inscripcion_hasta', $cohorte->inscripcion_hasta) }}">
                    @error('inscripcion_hasta')
                        <p class="form-error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Actualizar Cohorte</button>
                </div>
            </form>

            <script>
                // Preview de la duración fija: fecha_fin = fecha_inicio + 4 años.
                // (El servidor recalcula igual al guardar; esto es solo visual.)
                (function () {
                    var inicio = document.getElementById('fecha_inicio');
                    var fin = document.getElementById('fecha_fin');

                    function calcular() {
                        // Solo calcula con fecha bien formada (evita valores
                        // old() inválidos tipo "NaN-undefined-undefined").
                        if (!/^\d{4}-\d{2}-\d{2}$/.test(inicio.value)) {
                            fin.value = '';
                            return;
                        }

                        var partes = inicio.value.split('-');
                        var anio = parseInt(partes[0], 10) + 4;
                        var mes = partes[1];
                        var dia = partes[2];

                        // 29 de febrero en año no bisiesto → 28 de febrero.
                        var bisiesto = (anio % 4 === 0 && anio % 100 !== 0) || anio % 400 === 0;
                        if (mes === '02' && dia === '29' && !bisiesto) {
                            dia = '28';
                        }

                        fin.value = anio + '-' + mes + '-' + dia;
                    }

                    inicio.addEventListener('change', calcular);
                    calcular(); // valor inicial (old() o precargado)
                })();
            </script>
        </div>
    </section>
</x-layout>
