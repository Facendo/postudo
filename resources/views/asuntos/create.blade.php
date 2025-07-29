<x-layout title='PostUDO || Registrar Asunto'>
    <section class="login-page-container">
        <div class="registration-form">
            <h2 class="registration-form-title">Registrar Asunto</h2>

            <form action="{{ route('asuntos.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre del Asunto</label>
                    <input type="text" id="nombre" name="nombre" class="form-input" placeholder="Ej: Inscripción de Materias" value="{{ old('nombre') }}" required>
                </div>

                <div class="form-group">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea id="descripcion" name="descripcion" class="form-input" placeholder="Ej: Proceso de inscripción de asignaturas para el nuevo periodo." rows="3">{{ old('descripcion') }}</textarea>
                </div>

                
                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Registrar Asunto</button>
                </div>
            </form>
        </div>
    </section>
</x-layout>
