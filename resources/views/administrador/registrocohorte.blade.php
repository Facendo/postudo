<x-layout title='PostUDO || Registro de Cohorte'>
    <section class="login-page-container">
        <div class="registration-form">
            <h2 class="registration-form-title">Registro de Cohorte</h2>

            <form action="{{ route('administrador.gestioncohorte.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="codigo_cohorte" class="form-label">Código de Cohorte</label>
                    <input type="text" id="codigo_cohorte" name="codigo_cohorte" class="form-input" placeholder="Ej: C-2025-INF-01" required>
                </div>

                
                <input type="hidden" id="codigo_postgrado" name="codigo_postgrado" class="form-input"  value="{{ $postgrado->id_postgrado }}" >
                
                <div class="form-group">
                    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="nro_de_cohorte" class="form-label">Número de Cohorte</label>
                    <input type="number" id="nro_de_cohorte" name="nro_de_cohorte" class="form-input" placeholder="Ej: 1" min="1" required>
                </div>
                
                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Registrar Cohorte</button>
                </div>
            </form>
        </div>
    </section>
</x-layout>