<x-layout title='PostUDO || Registro de Materia'>
    <section class="login-page-container">
        <div class="registration-form">
            <h2 class="registration-form-title">Registro de Materia</h2>

            {{-- Form for subject registration --}}
            <form action="{{ route('administrador.gestionmaterias.store') }}" method="POST">
                @csrf
                
                {{-- Código de Materia --}}
                <div class="form-group">
                    <label for="codigo_materia" class="form-label">Código de Materia</label>
                    <input type="number" id="codigo_materia" name="codigo_materia" class="form-input" placeholder="Ej: 101" required>
                </div>

                {{-- Nombre de la Materia --}}
                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre de la Materia</label>
                    <input type="text" id="nombre" name="nombre" class="form-input" placeholder="Ej: Programación Avanzada" required>
                </div>
                
                <input type="hidden" name="codigo_cohorte" value="{{ $cohorte->codigo_cohorte }}">

                {{-- Selección de Especialidad --}}
                
                <div class="form-group">
                    <label for="prelacion" class="form-label">Prelación</label>
                    <select id="prelacion" name="prelacion" class="form-select" required>
                        @empty ($materias)
                            <option value="">No hay materias disponibles</option>
                        @else
                            <option value="0">Ninguna (sin prelación)</option>
                            @foreach ($materias as $materia)
                                <option value="{{ ($materia->codigo_materia) }}">{{ $materia->nombre }}</option>
                            @endforeach
                        @endempty
                    </select>
                </div>

                {{-- Submit Button --}}
                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Registrar Materia</button>
                </div>

            </form>
        </div>
    </section>


    
</x-layout>