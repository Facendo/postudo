<x-layout title='PostUDO || Registro de Cohorte'>
    <section class="login-page-container">
        <div class="registration-form">
            <h2 class="registration-form-title">Registro de Cohorte</h2>

            @php
                // Desde «Detalles del Postgrado» solo se muestran las menciones de ese
                // postgrado; desde el listado (sin parámetro) se muestran todas,
                // agrupadas por postgrado en <optgroup>.
                $mencionesVisibles = $postgradoSel
                    ? $menciones->where('id_postgrado', $postgradoSel->id_postgrado)->values()
                    : $menciones;
                $grupos = $mencionesVisibles->groupBy('id_postgrado');
            @endphp

            @if ($grupos->isEmpty())
                <p class="form-error-message" style="text-align: center;">
                    No hay menciones disponibles para crear cohortes. Primero crea una mención
                    desde la gestión de postgrados y vuelve aquí.
                </p>
                <div class="form-actions" style="justify-content: center;">
                    <a href="{{ route('administrador.gestion_postgrado') }}" class="button_body">Ir a Gestión de Postgrados</a>
                </div>
            @else
                @if ($postgradoSel)
                    <p style="color: var(--texto); margin-bottom: 15px; text-align: center;">
                        Postgrado: <strong>{{ $postgradoSel->nombre }}</strong>
                    </p>
                @endif

                <form action="{{ route('administrador.gestioncohorte.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="codigo_cohorte" class="form-label">Código de Cohorte</label>
                        <input type="text" id="codigo_cohorte" name="codigo_cohorte" class="form-input" placeholder="Ej: C-2025-INF-01" value="{{ old('codigo_cohorte') }}" required>
                        @error('codigo_cohorte')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- La cohorte cuelga de una mención (área -> postgrado -> mención);
                         el postgrado NO se envía: el controlador lo deriva de aquí. --}}
                    <div class="form-group">
                        <label for="codigo_mencion" class="form-label">Mención</label>
                        <select id="codigo_mencion" name="codigo_mencion" class="form-input" required>
                            <option value="" disabled {{ old('codigo_mencion') ? '' : 'selected' }}>Selecciona una mención</option>
                            @foreach ($grupos as $grupo)
                                @php $postgradoGrupo = $grupo->first()->postgrado; @endphp
                                <optgroup label="{{ $postgradoGrupo->nombre ?? 'Postgrado' }}">
                                    @foreach ($grupo as $mencion)
                                        <option value="{{ $mencion->id_mencion }}" {{ (string) old('codigo_mencion') === (string) $mencion->id_mencion ? 'selected' : '' }}>
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
                        <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-input" value="{{ old('fecha_inicio') }}" required>
                        @error('fecha_inicio')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Duración fija: la fecha de fin se calcula (fecha_inicio + 4 años)
                         y NO se envía al servidor; el controlador la recalcucla igual. --}}
                    <div class="form-group">
                        <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                        <input type="date" id="fecha_fin" class="form-input" value="" readonly tabindex="-1" style="background-color: rgba(255,255,255,0.15); cursor: not-allowed;">
                    </div>

                    {{-- Sin «Número de Cohorte»: el número lo asigna el
                         controlador al guardar (autoincrementa por mención). --}}

                    <div class="form-actions" style="justify-content: center;">
                        <button type="submit" class="submit-button">Registrar Cohorte</button>
                    </div>
                </form>

                <script>
                    // Preview de la duración fija: fecha_fin = fecha_inicio + 4 años.
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
            @endif
        </div>
    </section>
</x-layout>
