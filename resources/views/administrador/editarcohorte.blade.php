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

                <div class="form-group">
                    <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" class="form-input" value="{{ old('fecha_fin', $cohorte->fecha_fin) }}" required>
                    @error('fecha_fin')
                        <p class="form-error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nro_de_cohorte" class="form-label">Número de Cohorte</label>
                    <input type="number" id="nro_de_cohorte" name="nro_de_cohorte" class="form-input" placeholder="Ej: 1" min="1" value="{{ old('nro_de_cohorte', $cohorte->nro_de_cohorte) }}" required>
                    @error('nro_de_cohorte')
                        <p class="form-error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Actualizar Cohorte</button>
                </div>
            </form>
        </div>
    </section>
</x-layout>
