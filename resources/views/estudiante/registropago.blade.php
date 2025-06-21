<x-layout title="Registrar Pago">
    <div class="registration-page-container">
        <form action="{{ route('pago.store') }}" method="POST" class="registration-form">
            @csrf
            <h2 class="registration-form-title">Registrar Nuevo Pago</h2>

            <div class="form-group">
                <label for="cedula_pago" class="form-label">Cédula del Pagador</label>
                <input type="text" id="cedula" name="cedula" class="form-input" placeholder="Ej: V-12345678" required>
                @error('cedula')
                    <span class="form-error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="nombre" class="form-label">Nombre Completo del Pagador</label>
                <input type="text" id="nombre" name="nombre" class="form-input" placeholder="Ej: Juan Pérez" required>
                @error('nombre')
                    <span class="form-error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="banco_emisor" class="form-label">Banco Emisor</label>
                <input type="text" id="banco_emisor" name="banco_emisor" class="form-input" placeholder="Ej: Banco de Venezuela" required>
                @error('banco_emisor')
                    <span class="form-error-message">{{ $message }}</span>
                @enderror 
            </div>

            <div class="form-group">
                <label for="banco_receptor" class="form-label">Banco Receptor</label>
                <input type="text" id="banco_receptor" name="banco_receptor" class="form-input" placeholder="Ej: Banco Provincial" required>
                @error('banco_receptor')
                    <span class="form-error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="referencia" class="form-label">Referencia de Pago</label>
                <input type="text" id="referencia" name="referencia" class="form-input" placeholder="Ej: 000123456789" required>
                @error('referencia')
                    <span class="form-error-message">{{ $message }}</span>
                @enderror 
            </div>

            <div class="form-group">
                <label for="monto" class="form-label">Monto (Bs.)</label>
                <input type="number" id="monto" name="monto" class="form-input" step="0.01" min="0" placeholder="Ej: 150.50" required>
               @error('monto')
                    <span class="form-error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="asunto" class="form-label">Asunto / Descripción del Pago</label>
                <input type="text" id="asunto" name="asunto" class="form-input" placeholder="Ej: Inscripción Curso Verano" required>
                @error('asunto')
                    <span class="form-error-message">{{ $message }}</span>
                @enderror 
            </div>

            <div class="form-group">
                <label for="fecha_pago" class="form-label">Fecha del Pago</label>
                <input type="date" id="fecha_pago" name="fecha_pago" class="form-input" required>
                @error('fecha_pago')
                    <span class="form-error-message">{{ $message }}</span>
                @enderror 
            </div>

            <div class="form-actions">
                <button type="submit" class="submit-button">
                    <i class="fa-solid fa-paper-plane icon-left"></i> Registrar Pago
                </button>
            </div>
        </form>
    </div>
</x-layout>

