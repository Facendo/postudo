<x-layout title="PostUDO || Gestión de Áreas">

    <div class="page-content"
        style="width: 90%; max-width: 1200px; margin: 40px auto; display: flex; flex-direction: column; align-items: center;">

        {{-- Título de la sección (fuera de cualquier caja, como en Asuntos) --}}
        <div class="content_texto_bienvenida">
            <label>Áreas Registradas</label>
        </div>

        {{-- Toolbar: botón y contador, sueltos sobre el fondo --}}
        <div class="areas-toolbar">
            <button type="button" id="btnNewArea" class="button_body">
                <i class="fa-solid fa-plus icon-left"></i> Nueva Área
            </button>

            
        </div>

        {{-- Tabla de áreas: sola, sin caja propia --}}
        <div id="listViewAreas" class="contenedor-tabla">
            <table>
                <thead>
                    <tr>

                        <th>Nombre</th>
                        <th class="col-descripcion">Descripción</th>
                        <th>Postgrados</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($area as $item)
                        {{-- Fila del área (clic → despliega sus postgrados) --}}
                        <tr class="fila-area"
                            data-codigo="{{ $item->codigo }}"
                            data-nombre="{{ $item->nombre }}"
                            data-descripcion="{{ $item->descripcion }}"
                            tabindex="0">
                            <td class="nombre-area">
                                <i class="fa-solid fa-chevron-right flecha-fila"></i>
                                {{ $item->nombre }}
                            </td>
                            
                            <td class="descripcion-area">
                                {{ $item->descripcion ? \Illuminate\Support\Str::limit($item->descripcion, 90) : 'Sin descripción' }}
                            </td>
                            <td>
                                @php($totalPostgrados = $item->postgrados->count())
                                <span class="contador-postgrados {{ $totalPostgrados ? '' : 'contador-cero' }}"
                                    title="Postgrados de esta área">{{ $totalPostgrados }}</span>
                            </td>
                            <td class="table-actions">
                                <button type="button" class="btn-table-action editar-area" title="Editar área">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button type="button" class="btn-table-action eliminar-area"
                                    data-codigo="{{ $item->codigo }}" data-nombre="{{ $item->nombre }}"
                                    title="Eliminar área">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- Postgrados de la área: fila oculta que se despliega con el clic --}}
                        <tr class="fila-postgrados hidden" data-area="{{ $item->codigo }}">
                            <td colspan="4">
                                <div class="caja-postgrados">
                                    <div class="caja-postgrados-header">
                                        <h4 class="caja-postgrados-titulo">
                                            <i class="fa-solid fa-book-open"></i>
                                            Postgrados de «{{ $item->nombre }}»
                                        </h4>
                                        {{-- Registro de postgrado con esta área preseleccionada (?area=) --}}
                                        <a class="btn-nuevo-postgrado"
                                            href="{{ route('administrador.gestion_postgrado.create') }}?area={{ $item->codigo }}">
                                            <i class="fa-solid fa-plus"></i> Nuevo Postgrado
                                        </a>
                                    </div>

                                    @if ($item->postgrados->isEmpty())
                                        <p class="sin-postgrados">Este área no tiene postgrados registrados.</p>
                                    @else
                                        <ul class="lista-postgrados">
                                            @foreach ($item->postgrados as $post)
                                                <li>
                                                    <span class="post-nombre">{{ $post->nombre }}</span>
                                                    <span class="post-id">ID: {{ $post->id_postgrado }}</span>
                                                    <span class="post-menciones">
                                                        <i class="fa-solid fa-layer-group"></i>
                                                        Menciones:
                                                        {{ $post->menciones->count() ? $post->menciones->pluck('nombre')->join(', ') : 'ninguna' }}
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 30px; font-style: italic; opacity: 0.5;">
                                No hay áreas registradas. Pulsa «Nueva Área» para crear la primera.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ===== FORMULARIO DE CREAR / EDITAR ÁREA (se sustituye a la tabla) ===== --}}
        {{-- Si hubo errores de validación, el panel se muestra ya abierto --}}
        <div class="panel-glass panel-formulario {{ $errors->any() ? '' : 'hidden' }}" id="formArea">
            <div class="panel-glass-header">
                <h2 class="panel-glass-title" id="formAreaTitle">Registrar Nueva Área</h2>
                <button type="button" id="btnCancelArea" class="btn-glass">
                    <i class="fa-solid fa-arrow-left"></i> Volver al listado
                </button>
            </div>

            @if ($errors->any())
                <div class="alerta-glass" role="alert">
                    <strong><i class="fas fa-exclamation-triangle"></i> Revisa los siguientes campos:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="areaForm" class="formulario-area" action="{{ route('administrador.area.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="codigo" class="form-label">Código del área</label>
                    <input type="text" id="codigo" name="codigo" class="form-input" placeholder="Ej: ING"
                        value="{{ old('codigo') }}" required>
                </div>
                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre del Área</label>
                    <input type="text" id="nombre" name="nombre" class="form-input" placeholder="Ej: Ingeniería"
                        value="{{ old('nombre') }}" required>
                </div>
                <div class="form-group">
                    <label for="area_description_new" class="form-label">Descripción del Área</label>
                    <textarea id="area_description_new" name="descripcion" class="form-input" rows="4"
                        placeholder="¿De qué se encarga esta área?">{{ old('descripcion') }}</textarea>
                </div>
                <div class="form-actions" style="justify-content: center;">
                    <button type="submit" class="submit-button">Guardar Área</button>
                </div>
            </form>
        </div>

    </div>

    {{-- Formularios DELETE (uno por área): los envía el JS tras confirmar el impacto --}}
    <div id="deleteFormsContainer" style="display: none;">
        @foreach ($area as $item)
            <form id="delete-area-form-{{ $item->codigo }}"
                action="{{ route('administrador.area.destroy', $item->codigo) }}" method="POST">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    </div>

    <script>
        // Rutas y datos de validación que necesita admin_options.js
        // (Área -> Postgrado -> Mención)
        window.routes = {
            storeArea: "{{ route('administrador.area.store') }}",
            baseAreas: "{{ url('/coordinador/areas') }}"
        };
        window.erroresValidacion = @json($errors->keys());
        window.codigoAntiguo = @json(old('codigo'));
    </script>
    <script src="{{ asset('js/admin_options.js') }}"></script>

    <style>
        /* ===== TOOLBAR (fuera de caja, como en Asuntos) ===== */
        .areas-toolbar {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 18px;
            margin-bottom: 8px;
        }

        .contador-vista {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 77, 77, 0.1);
            border: 1px solid rgba(255, 77, 77, 0.25);
            border-radius: 50px;
            padding: 8px 20px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.88rem;
            color: rgba(255, 255, 255, 0.75);
        }

        .contador-vista i {
            color: #ff4d4d;
        }

        /* Tabla: ocupa todo el ancho disponible, sin caja propia */
        .contenedor-tabla {
            width: 100%;
        }

        /* ===== FILAS DE ÁREA EXPANDIBLES ===== */
        table .flecha-fila {
            color: rgba(255, 255, 255, 0.55);
            font-size: 0.8rem;
            margin-right: 9px;
            transition: color 0.2s ease;
        }

        table .fila-activa .flecha-fila {
            color: #ff6b6b;
        }

        table .fila-area.fila-activa {
            background: rgba(255, 77, 77, 0.1);
        }

        table .fila-area:focus-visible {
            outline: 2px solid rgba(255, 77, 77, 0.6);
            outline-offset: -2px;
        }

        table .nombre-area {
            font-weight: 600;
            color: #ffffff;
        }

        table .descripcion-area {
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.92em;
        }

        table .contador-postgrados {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            color: #ff8a73;
            font-size: 0.95rem;
        }

        table .contador-postgrados.contador-cero {
            color: rgba(255, 255, 255, 0.35);
            font-weight: 400;
        }

        /* Fila hija con los postgrados del área (banda sutil, sin cajas) */
        table .fila-postgrados td {
            background: rgba(0, 0, 0, 0.18);
            padding: 6px 25px 18px;
        }

        .caja-postgrados {
            border-top: 1px dashed rgba(255, 255, 255, 0.18);
            padding-top: 16px;
        }

        .caja-postgrados-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }

        .caja-postgrados-titulo {
            margin: 0;
            font-family: 'Outfit', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.85);
        }

        .caja-postgrados-titulo i {
            margin-right: 8px;
            color: #ff8a73;
        }

        /* Botón fantasma «Nuevo Postgrado» en la cabecera del área desplegada */
        .btn-nuevo-postgrado {
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
            text-decoration: none;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .btn-nuevo-postgrado:hover {
            background: rgba(255, 77, 77, 0.15);
            border-color: rgba(255, 77, 77, 0.5);
            color: #ffffff;
        }

        .sin-postgrados {
            margin: 0;
            color: rgba(255, 255, 255, 0.5);
            font-style: italic;
        }

        /* Lista de postgrados sin tarjetas: solo líneas separadoras */
        .lista-postgrados {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .lista-postgrados li {
            display: flex;
            flex-wrap: wrap;
            align-items: baseline;
            gap: 4px 14px;
            padding: 11px 6px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .lista-postgrados li:last-child {
            border-bottom: none;
        }

        .post-nombre {
            font-weight: 600;
            color: #ffffff;
        }

        .post-id {
            font-size: 0.78rem;
            color: rgba(255, 255, 255, 0.55);
        }

        .post-menciones {
            width: 100%;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.75);
        }

        /* ===== FORMULARIO (único cuadro de la vista cuando está abierto) ===== */
        .panel-glass {
            width: 100%;
            max-width: 760px;
            margin: 30px auto;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px 35px 25px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.04);
            animation: fadeInSlideUp 0.6s ease-out forwards;
            box-sizing: border-box;
        }

        .panel-glass-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding-bottom: 22px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .panel-glass-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.25);
        }

        .btn-glass {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 12px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-glass:hover {
            background: rgba(255, 77, 77, 0.15);
            border-color: rgba(255, 77, 77, 0.5);
            transform: translateY(-2px);
        }

        .formulario-area {
            padding-top: 24px;
        }

        /* Bloque de errores de validación */
        .alerta-glass {
            margin-top: 20px;
            padding: 14px 18px;
            background: rgba(224, 87, 87, 0.15);
            border: 1px solid rgba(224, 87, 87, 0.5);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 12px;
            color: #ffd9d9;
            font-size: 0.9rem;
        }

        .alerta-glass ul {
            margin: 8px 0 0 18px;
            padding: 0;
        }

        @media (max-width: 768px) {
            .contenedor-tabla {
                width: 100%;
            }

            table .col-descripcion,
            table .descripcion-area {
                display: none;
            }

            /* En móvil el CSS global convierte las celdas en bloques alineados
               a la derecha; se restaura la lectura normal en la fila de detalle */
            table .fila-postgrados td {
                padding: 6px 15px 16px;
                text-align: left;
            }
        }
    </style>

</x-layout>
