
<x-layout title='PostUDO || Postgrados'>
    <div class="page-content" style="width: 90%; max-width: 1200px; margin: 40px auto; display: flex; flex-direction: column; align-items: center;">
        {{-- Título de la sección --}}
        <div class="content_texto_bienvenida">
            <label>Listado de Postgrados</label>
        </div>

        {{-- Botón para crear un nuevo postgrado (no lo ve el coordinador de
             postgrado: crear postgrados es del general/administrador) --}}
        @if (Auth::user()->rol !== 'coordinador_postgrado')
            <div class="action-buttons-container">
                {{-- Se asume que la ruta para crear un postgrado es 'administrador.gestion_postgrado.create' --}}
                <a href="{{ route('administrador.gestion_postgrado.create') }}" class="button_body">
                    <i class="fa-solid fa-plus icon-left"></i> Nuevo Postgrado
                </a>
            </div>
        @endif

        {{-- Aviso si «Nueva mención» no pasa la validación (store() vuelve con back()) --}}
        @error('nombre')
            <p class="alerta-error-mencion">{{ $message }}</p>
        @enderror

        {{-- Tabla para mostrar los postgrados --}}
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Área</th>
                    <th>Detalles</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                {{--
                    Bucle @forelse para iterar sobre la colección de $postgrados.
                    Muestra una fila por cada postgrado.
                --}}
                @forelse ($postgrados as $postgrado)
                    {{-- Fila expandible: al pulsarla se despliegan sus menciones + el formulario de nueva mención --}}
                    <tr class="fila-postgrado" data-id="{{ $postgrado->id_postgrado }}" data-nombre="{{ $postgrado->nombre }}"
                        tabindex="0" aria-expanded="false">
                        <td>{{ $postgrado->id_postgrado }}</td>
                        <td class="nombre-postgrado">
                            <i class="fa-solid fa-chevron-right flecha-fila" aria-hidden="true"></i>
                            {{ $postgrado->nombre }}
                        </td>
                        <td>{{ $postgrado->descripcion }}</td>
                        <td>{{ $postgrado->area->nombre ?? 'Sin área' }}</td>
                        <td class="table-actions">
                            {{-- Enlace a la vista de detalles del postgrado --}}
                            <a href="{{ route('administrador.gestion_postgrado.showdetalles', $postgrado->id_postgrado) }}" class="btn-table-action" title="Ver Detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                        
                        {{-- Columna de acciones con botones para Editar y Eliminar
                             (no los ve el coordinador de postgrado: tocar el
                             postgrado mismo es del general/administrador) --}}
                        <td class="table-actions">
                            @if (Auth::user()->rol !== 'coordinador_postgrado')
                                {{-- Botón de Editar --}}
                                {{-- Se asume que la ruta para editar es 'administrador.gestion_postgrado.edit' --}}
                                <a href="{{ route('administrador.gestion_postgrado.edit', $postgrado->id_postgrado) }}" class="btn-table-action" title="Editar">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>

                                {{-- Botón de Eliminar (usando un formulario para solicitudes DELETE) --}}
                                {{-- Se asume que la ruta para eliminar es 'administrador.gestion_postgrado.destroy' --}}
                                <form action="{{ route('administrador.gestion_postgrado.destroy', $postgrado->id_postgrado) }}" method="POST" class="inline-form"
                                    data-eliminar-postgrado="{{ $postgrado->id_postgrado }}" data-nombre="{{ $postgrado->nombre }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-table-action" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>

                    {{-- Menciones del postgrado: fila oculta que se despliega con el clic --}}
                    <tr class="fila-menciones hidden" data-postgrado="{{ $postgrado->id_postgrado }}">
                        <td colspan="6">
                            <div class="caja-menciones">
                                <div class="caja-menciones-header">
                                    <h4 class="caja-menciones-titulo">
                                        <i class="fa-solid fa-layer-group"></i>
                                        Menciones de «{{ $postgrado->nombre }}»
                                    </h4>
                                </div>

                                @if ($postgrado->menciones->isEmpty())
                                    <p class="sin-menciones">Este postgrado aún no tiene menciones.</p>
                                @else
                                    <ul class="lista-menciones">
                                        @foreach ($postgrado->menciones as $mencion)
                                            <li>
                                                <span class="mencion-nombre">{{ $mencion->nombre }}</span>
                                                {{-- Eliminar mención: mismo aviso de estudiantes que en Detalles --}}
                                                <form action="{{ route('administrador.mencion.destroy', $mencion) }}" method="POST" class="inline-form"
                                                    data-eliminar-mencion data-nombre="{{ $mencion->nombre }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-table-action" title="Eliminar mención">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                                {{-- Mini-formulario: nueva mención anclada a este postgrado (mismo patrón
                                     que «Nuevo Postgrado» en la fila desplegada de Áreas) --}}
                                <form action="{{ route('administrador.mencion.store', $postgrado->id_postgrado) }}" method="POST"
                                    class="form-nueva-mencion">
                                    @csrf
                                    <input type="text" name="nombre" class="form-input" maxlength="255"
                                        placeholder="Nombre de la nueva mención" required>
                                    <button type="submit" class="btn-nueva-mencion">
                                        <i class="fa-solid fa-plus"></i> Guardar mención
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    {{--
                        Este bloque se muestra si la colección $postgrados está vacía.
                        El colspan se ajusta al número de columnas de la tabla.
                    --}}
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px;">
                            {{-- Mensaje según rol: para el coordinador de postgrado una
                                 tabla vacía casi siempre significa "sin asignación" --}}
                            {{ Auth::user()->rol === 'coordinador_postgrado'
                                ? 'No tienes postgrados asignados actualmente.'
                                : 'No hay postgrados registrados.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <style>
        /* ===== FILAS DE POSTGRADO EXPANDIBLES (menciones) ===== */
        table .fila-postgrado {
            cursor: pointer;
        }

        table .flecha-fila {
            color: rgba(255, 255, 255, 0.55);
            font-size: 0.8rem;
            margin-right: 9px;
            transition: color 0.2s ease;
        }

        table .fila-activa .flecha-fila {
            color: #ff9f2e;
        }

        table .fila-postgrado.fila-activa {
            background: rgba(247, 148, 29, 0.1);
        }

        table .fila-postgrado:focus-visible {
            outline: 2px solid rgba(247, 148, 29, 0.6);
            outline-offset: -2px;
        }

        table .nombre-postgrado {
            font-weight: 600;
            color: #ffffff;
        }

        /* Fila desplegable con las menciones del postgrado */
        table .fila-menciones td {
            background: rgba(0, 0, 0, 0.18);
            padding: 6px 25px 18px;
        }

        .caja-menciones {
            border-top: 1px dashed rgba(255, 255, 255, 0.18);
            padding-top: 16px;
        }

        .caja-menciones-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }

        .caja-menciones-titulo {
            margin: 0;
            font-family: 'Outfit', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.85);
        }

        .caja-menciones-titulo i {
            margin-right: 8px;
            color: #ff9f2e;
        }

        .sin-menciones {
            margin: 0 0 14px;
            color: rgba(255, 255, 255, 0.5);
            font-style: italic;
        }

        .lista-menciones {
            list-style: none;
            margin: 0 0 16px;
            padding: 0;
        }

        .lista-menciones li {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 8px 14px;
            padding: 11px 6px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .lista-menciones li:last-child {
            border-bottom: none;
        }

        .mencion-nombre {
            font-weight: 600;
            color: #ffffff;
        }

        /* Mini-formulario de nueva mención (mismo estilo fantasma que «Nuevo Postgrado») */
        .form-nueva-mencion {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .form-nueva-mencion .form-input {
            max-width: 320px;
        }

        .btn-nueva-mencion {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 16px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 10px;
            background: transparent;
            color: rgba(255, 255, 255, 0.85);
            font-family: 'Outfit', sans-serif;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .btn-nueva-mencion:hover {
            background: rgba(247, 148, 29, 0.15);
            border-color: rgba(247, 148, 29, 0.5);
            color: #ffffff;
        }

        /* Aviso de validación del nombre de la mención */
        .alerta-error-mencion {
            margin: 0 0 14px;
            padding: 10px 18px;
            background: rgba(255, 77, 77, 0.12);
            border: 1px solid rgba(255, 77, 77, 0.4);
            border-radius: 8px;
            color: #ffb3b3;
            font-size: 0.9rem;
        }
    </style>

    <script>
        // Confirmación previa al borrado: muestra todo lo que se elimina en cascada.
        document.querySelectorAll('form[data-eliminar-postgrado]').forEach(form => {
            form.addEventListener('submit', async (evento) => {
                if (form.dataset.confirmado === '1') return;

                evento.preventDefault();

                let mensaje = `¿Eliminar el postgrado "${form.dataset.nombre}"?`;

                try {
                    const respuesta = await fetch(
                        `{{ url('/administrador/gestion_postgrado') }}/${encodeURIComponent(form.dataset.eliminarPostgrado)}/impacto`,
                        { headers: { 'Accept': 'application/json' } }
                    );

                    if (respuesta.ok) {
                        const impacto = await respuesta.json();
                        const partes = [];
                        if (impacto.menciones) partes.push(`${impacto.menciones} mención(es)`);
                        if (impacto.cohortes) partes.push(`${impacto.cohortes} cohorte(s)`);

                        if (partes.length) {
                            mensaje += '\n\nSe eliminará en cascada:\n- ' + partes.join('\n- ');
                        }

                        if (impacto.estudiantes_desasignados) {
                            mensaje += `\n\n${impacto.estudiantes_desasignados} estudiante(s) quedarán SIN mención asignada (no se borran).`;
                        }
                    }
                } catch (error) {
                    // Si falla la consulta de impacto, se pide confirmación básica.
                }

                if (confirm(mensaje)) {
                    form.dataset.confirmado = '1';
                    form.submit();
                }
            });
        });

        // Desplegar/cerrar las menciones de un postgrado al pulsar su fila
        // (mismo sistema que en la vista de Áreas; se usa la clase .hidden
        // porque en móvil el CSS global de tablas fuerza "tr { display: block }").
        document.querySelectorAll('tr.fila-postgrado').forEach(fila => {
            const alternar = () => {
                const detalle = fila.nextElementSibling;
                if (!detalle || !detalle.classList.contains('fila-menciones')) return;

                const estabaOculta = detalle.classList.contains('hidden');
                detalle.classList.toggle('hidden', !estabaOculta);

                fila.classList.toggle('fila-activa', estabaOculta);
                fila.setAttribute('aria-expanded', estabaOculta ? 'true' : 'false');

                const flecha = fila.querySelector('.flecha-fila');
                if (flecha) {
                    flecha.classList.toggle('fa-chevron-right', !estabaOculta);
                    flecha.classList.toggle('fa-chevron-down', estabaOculta);
                }
            };

            // Clic en la fila (los botones de Ver/Editar/Eliminar no deben desplegarla)
            fila.addEventListener('click', evento => {
                if (evento.target.closest('.table-actions')) return;
                alternar();
            });

            // Accesibilidad: Enter o Espacio sobre la fila
            fila.addEventListener('keydown', evento => {
                if (evento.target !== fila) return;
                if (evento.key !== 'Enter' && evento.key !== ' ') return;
                evento.preventDefault();
                alternar();
            });
        });

        // Confirmación previa al borrado de una mención desde la lista desplegada.
        document.querySelectorAll('form[data-eliminar-mencion]').forEach(form => {
            form.addEventListener('submit', evento => {
                if (form.dataset.confirmado === '1') return;

                evento.preventDefault();

                const mensaje = `¿Eliminar la mención "${form.dataset.nombre}"? Los estudiantes que la tengan asignada quedarán SIN mención (no se borran). Si la mención tiene cohortes, no se podrá eliminar: primero elimínalas o cámbiales de mención.`;

                if (confirm(mensaje)) {
                    form.dataset.confirmado = '1';
                    form.submit();
                }
            });
        });
    </script>
</x-layout>
