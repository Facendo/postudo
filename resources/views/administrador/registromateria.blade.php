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
                
                <div class="form-group">
                    <label for="especialidad" class="form-label">Especialidad</label>
                    <select id="especialidad" name="codigo_especialidad" class="form-select" required>
                        @empty ($especialidades)
                            <option value="">No hay especialidades disponibles</option>
                        @else
                            @foreach ($especialidades as $especialidad)
                                <option value="{{ ($especialidad->codigo_especialidad) }}">{{ $especialidad->nombre_carrera }}</option>
                            @endforeach
                        @endempty
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="prelacion" class="form-label">Prelación</label>
                    <select id="prelacion" name="prelacion" class="form-select" required>
                        @empty ($materias)
                            <option value="">No hay materias disponibles</option>
                        @else
                            @foreach ($materias as $materia)
                                <option value="{{ ($materia->codigo_materia) }}">{{ $materia->nombre }}</option>
                            @endforeach
                        @endempty
                </div>
                
                {{-- Submit Button --}}
                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Registrar Materia</button>
                </div>

            </form>
        </div>
    </section>


    
</x-layout>