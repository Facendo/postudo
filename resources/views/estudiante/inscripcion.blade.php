<x-layout title='PostUDO || Registro de Estudiante'>
    <section class="login-page-container">
        <div class="registration-form">
            <h2 class="registration-form-title">Inscripción de Estudiante a Oferta Académica</h2>

            {{-- Formulario de inscripción a una cohorte --}}
            {{-- NOTA: el envío (estudiante.inscribir) aún no está implementado; --}}
            {{-- el rediseño completo del sistema de inscripción queda pendiente (ver MEMORY). --}}
            <form action="{{ route('estudiante.inscribir') }}" method="POST">
                @csrf

                {{-- Campo para seleccionar el Postgrado --}}
                <div class="form-group">
                    <label for="id_postgrado" class="form-label">Postgrado</label>
                    <select id="id_postgrado" name="id_postgrado" class="form-input" required>
                        <option value="">Selecciona un Postgrado</option>
                        @forelse($postgrados as $postgrado)
                            <option value="{{ $postgrado->id_postgrado }}" {{ old('id_postgrado') == $postgrado->id_postgrado ? 'selected' : '' }}>
                                {{ $postgrado->nombre }}
                            </option>
                        @empty
                            <option value="" disabled>No hay Postgrados registrados.</option>
                        @endforelse
                    </select>
                    @error('id_postgrado')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Campo para seleccionar la Cohorte --}}
                <div class="form-group">
                    <label for="codigo_cohorte" class="form-label">Cohorte</label>
                    <select id="codigo_cohorte" name="codigo_cohorte" class="form-input" required disabled>
                        <option value="">Selecciona una Cohorte</option>
                    </select>
                    @error('codigo_cohorte')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Inscribirse</button>
                </div>
            </form>
        </div>
    </section>

    <script>
        // Datos pasados desde el controlador: postgrados y cohortes.
        // Cascado simple postgrado -> cohorte (materias y secciones ya no existen).
        const allPostgrados = @json($postgrados ?? []);
        const allCohortes = @json($cohortes ?? []);

        // Elementos del DOM
        const postgradoSelect = document.getElementById('id_postgrado');
        const cohorteSelect = document.getElementById('codigo_cohorte');

        // Función para resetear y deshabilitar un select
        function resetAndDisableSelect(selectElement, defaultText) {
            selectElement.innerHTML = `<option value="">${defaultText}</option>`;
            selectElement.disabled = true;
        }

        // Función para poblar un select
        function populateSelect(selectElement, items, idKey, nameKey, defaultText) {
            selectElement.innerHTML = `<option value="">${defaultText}</option>`;
            if (items.length > 0) {
                items.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item[idKey];
                    option.textContent = item[nameKey];
                    selectElement.appendChild(option);
                });
                selectElement.disabled = false;
            } else {
                selectElement.innerHTML = `<option value="" disabled>No hay ${defaultText.toLowerCase().replace('selecciona una ', '')} disponibles.</option>`;
                selectElement.disabled = true;
            }
        }

        // Event Listener para Postgrado: filtra las cohortes del postgrado elegido.
        postgradoSelect.addEventListener('change', function() {
            const selectedPostgradoId = this.value;

            resetAndDisableSelect(cohorteSelect, 'Selecciona una Cohorte');

            if (selectedPostgradoId) {
                const filteredCohortes = allCohortes.filter(cohorte => cohorte.codigo_postgrado == selectedPostgradoId);
                populateSelect(cohorteSelect, filteredCohortes, 'codigo_cohorte', 'codigo_cohorte', 'Selecciona una Cohorte');
            }
        });

        // Re-selección de valores antiguos si la validación falla.
        document.addEventListener('DOMContentLoaded', () => {
            const oldPostgradoId = "{{ old('id_postgrado') }}";
            const oldCohorteId = "{{ old('codigo_cohorte') }}";

            if (oldPostgradoId) {
                postgradoSelect.value = oldPostgradoId;
                postgradoSelect.dispatchEvent(new Event('change'));

                setTimeout(() => {
                    if (oldCohorteId) {
                        cohorteSelect.value = oldCohorteId;
                    }
                }, 100);
            }
        });
    </script>
</x-layout>
