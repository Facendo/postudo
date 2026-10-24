<x-layout title="PostUDO || Postgrado">

    <section class="content_texto_bienvenida">
        <label>Bienvenido al Catálogo de Postgrados UDO</label>
    </section>

    <p class="catalogo-entrada">
        Explora las áreas de postgrado de la UDO: despliega cada una para ver sus
        postgrados y las menciones de cada uno.
    </p>

    {{-- Áreas -> Postgrados -> Menciones en un acordeón (todas abiertas por defecto). --}}
    {{-- Se usa <details>/<summary> nativo: funciona sin JavaScript y es accesible. --}}
    <div class="catalogo">
        @forelse ($areas as $area)
            <details class="catalogo-area" open>
                <summary class="catalogo-area-cabecera">
                    <i class="fa-solid fa-layer-group catalogo-icono"></i>
                    <span class="catalogo-area-nombre">{{ $area->nombre }}</span>
                    <span class="catalogo-contador">
                        {{ $area->postgrados->count() }}
                        {{ $area->postgrados->count() === 1 ? 'postgrado' : 'postgrados' }}
                    </span>
                    <i class="fa-solid fa-chevron-down catalogo-flecha"></i>
                </summary>

                <div class="catalogo-area-cuerpo">
                    @if ($area->postgrados->isEmpty())
                        <p class="catalogo-vacio">Este área aún no tiene postgrados registrados.</p>
                    @else
                        <ul class="catalogo-postgrados">
                            @foreach ($area->postgrados as $postgrado)
                                <li class="catalogo-postgrado">
                                    <div class="catalogo-postgrado-cabecera">
                                        <i class="fa-solid fa-graduation-cap catalogo-icono"></i>
                                        <span>{{ $postgrado->nombre }}</span>
                                        <span class="catalogo-contador">
                                            {{ $postgrado->menciones->count() }}
                                            {{ $postgrado->menciones->count() === 1 ? 'mención' : 'menciones' }}
                                        </span>
                                    </div>

                                    @if ($postgrado->menciones->isEmpty())
                                        <p class="catalogo-vacio">Este postgrado aún no tiene menciones.</p>
                                    @else
                                        <ul class="catalogo-menciones">
                                            @foreach ($postgrado->menciones as $mencion)
                                                <li>
                                                    <i class="fa-solid fa-bookmark"></i>
                                                    <span>{{ $mencion->nombre }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </details>
        @empty
            <p class="catalogo-vacio catalogo-vacio-global">No hay áreas registradas por el momento.</p>
        @endforelse
    </div>

</x-layout>
