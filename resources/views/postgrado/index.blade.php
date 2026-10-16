<x-layout title="PostUDO || Postgrado">
<body>

    <main>
        <section class="content_texto_bienvenida">
            <label>Bienvenido al Catálogo de Postgrados UDO</label>
        </section>

        <section class="action-buttons-container">
            <button id="btnArea" class="button_body active-button">
                Áreas de Postgrado
            </button>
            <button id="btnPostgrado" class="button_body hidden">
                Postgrados por Área
            </button>
            <button id="btnMencion" class="button_body hidden">
                Menciones por Postgrado
            </button>
        </section>

        {{-- 1) ÁREAS --}}
        <section id="infoArea" class="info-section active">
            <h3>Áreas de Postgrado de la UDO</h3>
            <ul class="info-list">
                @forelse ($areas as $area)
                    <li>
                        <a href="#" class="area-link" data-area="{{ $area->codigo }}" data-nombre="{{ $area->nombre }}">
                            {{ $area->nombre }}
                        </a>
                    </li>
                @empty
                    <li>No hay áreas registradas por el momento.</li>
                @endforelse
            </ul>
        </section>

        {{-- 2) POSTGRADOS DE CADA ÁREA --}}
        <section id="infoPostgrado" class="info-section">
            <h3>Postgrados por Área</h3>
            @forelse ($areas as $area)
                <div class="postgrado-group" data-area="{{ $area->codigo }}" style="display: none;">
                    <ul class="info-list">
                        <li>
                            <a href="#" class="area-return-link" data-nombre="{{ $area->nombre }}">
                                {{ $area->nombre }}
                            </a>
                            @if ($area->postgrados->isEmpty())
                                <ul class="info-list-nested">
                                    <li>No hay postgrados registrados en esta área.</li>
                                </ul>
                            @else
                                <ul class="info-list-nested">
                                    @foreach ($area->postgrados as $postgrado)
                                        <li>
                                            <a href="#" class="postgrado-link"
                                               data-postgrado="{{ $postgrado->id_postgrado }}"
                                               data-area="{{ $area->codigo }}"
                                               data-nombre="{{ $postgrado->nombre }}">
                                                {{ $postgrado->nombre }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    </ul>
                </div>
            @empty
                <ul class="info-list">
                    <li>No hay áreas registradas por el momento.</li>
                </ul>
            @endforelse
        </section>

        {{-- 3) MENCIONES DE CADA POSTGRADO --}}
        <section id="infoMencion" class="info-section">
            <h3>Menciones por Postgrado</h3>
            @forelse ($areas as $area)
                @foreach ($area->postgrados as $postgrado)
                    <div class="mencion-group" data-postgrado="{{ $postgrado->id_postgrado }}" data-area="{{ $area->codigo }}" style="display: none;">
                        <ul class="info-list">
                            <li>
                                <a href="#" class="postgrado-return-link" data-area="{{ $area->codigo }}">
                                    {{ $postgrado->nombre }}
                                </a>
                                @if ($postgrado->menciones->isEmpty())
                                    <ul class="info-list-nested">
                                        <li>Este postgrado aún no tiene menciones.</li>
                                    </ul>
                                @else
                                    <ul class="info-list-nested">
                                        @foreach ($postgrado->menciones as $mencion)
                                            <li>{{ $mencion->nombre }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        </ul>
                    </div>
                @endforeach
            @empty
                <ul class="info-list">
                    <li>No hay postgrados registrados por el momento.</li>
                </ul>
            @endforelse
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const secciones = {
                area: document.getElementById('infoArea'),
                postgrado: document.getElementById('infoPostgrado'),
                mencion: document.getElementById('infoMencion')
            };
            const botones = {
                area: document.getElementById('btnArea'),
                postgrado: document.getElementById('btnPostgrado'),
                mencion: document.getElementById('btnMencion')
            };

            let areaActual = null;
            let nombreAreaActual = '';
            let postgradoActual = null;
            let nombrePostgradoActual = '';

            function mostrar(seccion) {
                Object.values(secciones).forEach(s => s.classList.remove('active'));
                secciones[seccion].classList.add('active');

                Object.entries(botones).forEach(([clave, boton]) => {
                    boton.classList.toggle('active-button', clave === seccion);
                });
            }

            function mostrarPostgradosDeArea(codigoArea, nombreArea) {
                areaActual = codigoArea;
                nombreAreaActual = nombreArea;

                document.querySelectorAll('.postgrado-group').forEach(grupo => {
                    grupo.style.display = grupo.dataset.area === String(codigoArea) ? '' : 'none';
                });

                botones.postgrado.classList.remove('hidden');
                botones.postgrado.textContent = `Postgrados: ${nombreAreaActual}`;
                mostrar('postgrado');
            }

            function mostrarMencionesDePostgrado(idPostgrado, codigoArea, nombrePostgrado) {
                postgradoActual = idPostgrado;
                nombrePostgradoActual = nombrePostgrado;

                document.querySelectorAll('.mencion-group').forEach(grupo => {
                    grupo.style.display = grupo.dataset.postgrado === String(idPostgrado) ? '' : 'none';
                });

                botones.mencion.classList.remove('hidden');
                botones.mencion.textContent = `Menciones: ${nombrePostgradoActual}`;
                mostrar('mencion');
            }

            // Áreas -> Postgrados
            document.querySelectorAll('.area-link').forEach(enlace => {
                enlace.addEventListener('click', evento => {
                    evento.preventDefault();
                    mostrarPostgradosDeArea(enlace.dataset.area, enlace.dataset.nombre);
                });
            });

            // Postgrados -> Menciones
            document.querySelectorAll('.postgrado-link').forEach(enlace => {
                enlace.addEventListener('click', evento => {
                    evento.preventDefault();
                    mostrarMencionesDePostgrado(enlace.dataset.postgrado, enlace.dataset.area, enlace.dataset.nombre);
                });
            });

            // Volver de Menciones -> Postgrados del área
            document.querySelectorAll('.postgrado-return-link').forEach(enlace => {
                enlace.addEventListener('click', evento => {
                    evento.preventDefault();
                    mostrarPostgradosDeArea(enlace.dataset.area, nombreAreaActual || 'la');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            });

            // Volver de Postgrados -> Áreas
            document.querySelectorAll('.area-return-link').forEach(enlace => {
                enlace.addEventListener('click', evento => {
                    evento.preventDefault();
                    mostrar('area');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            });

            // Navegación por los botones superiores
            botones.area.addEventListener('click', () => mostrar('area'));
            botones.postgrado.addEventListener('click', () => {
                if (areaActual !== null) mostrarPostgradosDeArea(areaActual, nombreAreaActual);
                else mostrar('postgrado');
            });
            botones.mencion.addEventListener('click', () => {
                if (postgradoActual !== null) mostrarMencionesDePostgrado(postgradoActual, areaActual, nombrePostgradoActual);
                else mostrar('mencion');
            });
        });
    </script>
</body>
</x-layout>
