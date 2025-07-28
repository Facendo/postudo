<x-layout title='PostUDO || Editar Cohorte'>
    <section class="login-page-container">
        <div class="registration-form">
            <h2 class="registration-form-title">Editar Cohorte</h2>

            <form action="{{ route('administrador.gestioncohorte.update', $cohorte->codigo_cohorte) }}" method="POST">
                @csrf
                @method('PUT') {{-- Use PUT method for updates --}}

                <div class="form-group">
                    <label for="codigo_cohorte" class="form-label">Código de Cohorte</label>
                    <input type="text" id="codigo_cohorte" name="codigo_cohorte" class="form-input" placeholder="Ej: C-2025-INF-01" value="{{ old('codigo_cohorte', $cohorte->codigo_cohorte) }}" required>
                    {{-- The codigo_cohorte is typically the primary key and might not be editable --}}
                </div>

                <div class="form-group">
                    <label for="codigo_postgrado" class="form-label">Código de Postgrado</label>
                    <input type="text" id="codigo_postgrado" name="codigo_postgrado" class="form-input" placeholder="Ej: POST-INF-2024" value="{{ old('codigo_postgrado', $cohorte->codigo_postgrado) }}" required>
                </div>

                <div class="form-group">
                    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-input" value="{{ old('fecha_inicio', $cohorte->fecha_inicio) }}" required>
                </div>

                <div class="form-group">
                    <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" class="form-input" value="{{ old('fecha_fin', $cohorte->fecha_fin) }}" required>
                </div>

                <div class="form-group">
                    <label for="nro_de_cohorte" class="form-label">Número de Cohorte</label>
                    <input type="number" id="nro_de_cohorte" name="nro_de_cohorte" class="form-input" placeholder="Ej: 1" min="1" value="{{ old('nro_de_cohorte', $cohorte->nro_de_cohorte) }}" required>
                </div>
                
                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Actualizar Cohorte</button>
                </div>
            </form>
        </div>
    </section>
</x-layout>