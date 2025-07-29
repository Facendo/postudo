<x-layout title='PostUDO || Editar Asunto'>
    <section class="login-page-container">
        <div class="registration-form">
            <h2 class="registration-form-title">Editar Asunto</h2>

            {{-- The action route for updating an existing resource is typically 'resource.update' --}}
            <form action="{{ route('asuntos.update', $asunto->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Use PUT method for updates --}}

                {{-- The 'id' is usually the primary key and not directly editable. 
                     It's included in the route, but not as an editable field here. --}}
                <input type="hidden" name="id" value="{{ $asunto->id }}">

                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre del Asunto</label>
                    <input type="text" id="nombre" name="nombre" class="form-input" placeholder="Ej: Inscripción de Materias" value="{{ old('nombre', $asunto->nombre) }}" required>
                </div>

                <div class="form-group">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea id="descripcion" name="descripcion" class="form-input" placeholder="Ej: Proceso de inscripción de asignaturas para el nuevo periodo." rows="3">{{ old('descripcion', $asunto->descripcion) }}</textarea>
                </div>
                
                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Actualizar Asunto</button>
                </div>
            </form>
        </div>
    </section>
</x-layout>
