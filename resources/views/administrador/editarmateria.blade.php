<x-layout title='PostUDO || Editar Materia'>
    <section class="login-page-container">
        <div class="registration-form">
            <h2 class="registration-form-title">Editar Materia: {{ $materia->nombre }}</h2>

            {{-- Form for subject editing --}}
            {{-- Action points to the update route with the materia's primary key --}}
            <form action="{{ route('administrador.gestionmaterias.update', $materia->codigo_materia) }}" method="POST">
                @csrf
                @method('PUT') {{-- Use PATCH method for updates, Laravel handles this via a hidden input --}}
                
                {{-- Código de Materia (Typically read-only for primary key to avoid accidental changes) --}}
                <div class="form-group">
                    <label for="codigo_materia" class="form-label">Código de Materia</label>
                    <input type="number" id="codigo_materia" name="codigo_materia" class="form-input" 
                           placeholder="Ej: 101" required 
                           value="{{ old('codigo_materia', $materia->codigo_materia) }}">
                    @error('codigo_materia')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nombre de la Materia --}}
                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre de la Materia</label>
                    <input type="text" id="nombre" name="nombre" class="form-input" 
                           placeholder="Ej: Programación Avanzada" required 
                           value="{{ old('nombre', $materia->nombre) }}">
                    @error('nombre')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                

                {{-- Prelación (Optional) --}}
                <div class="form-group">
                    <label for="prelacion" class="form-label">Prelación (Código Materia)</label>
                    <input type="number" id="prelacion" name="prelacion" class="form-input" 
                           placeholder="Ej: 100 (opcional)" 
                           value="{{ old('prelacion', $materia->prelacion) }}">
                    @error('prelacion')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Submit Button --}}
                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Actualizar Materia</button>
                </div>

            </form>
        </div>
    </section>
</x-layout>