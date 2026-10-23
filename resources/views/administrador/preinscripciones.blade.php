<x-layout title="PostUDO || Preinscripciones">
    <style>
        /* Estilos locales del listado de preinscripciones (solo esta página) */
        .estado-prein {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 14px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .estado-prein.pendiente {
            background: rgba(247, 148, 29, 0.15);
            border: 1px solid rgba(247, 148, 29, 0.45);
            color: #f7941d;
        }

        .estado-prein.aprobada {
            background: rgba(40, 167, 69, 0.15);
            border: 1px solid rgba(40, 167, 69, 0.45);
            color: #4ac97b;
        }

        .estado-prein.rechazada {
            background: rgba(220, 53, 69, 0.15);
            border: 1px solid rgba(220, 53, 69, 0.45);
            color: #f16c79;
        }

        .acciones-prein {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .acciones-prein form {
            display: inline;
        }

        .acciones-prein button {
            border: none;
            cursor: pointer;
            font: inherit;
        }
    </style>

    <div class="page-content"
        style="width: 92%; max-width: 1250px; margin: 40px auto; display: flex; flex-direction: column; align-items: center;">

        <div class="content_texto_bienvenida">
            <label>Preinscripciones Recibidas</label>
        </div>

        <div style="margin: 10px 0 25px; color: #9fb3d1; font-size: 0.92rem; text-align: center;">
            Aprobar una preinscripción habilita al aspirante para su inscripción; la planilla .docx
            puede regenerarse y descargarse en cualquier momento.
        </div>

        <div class="contenedor-tabla">
            <table>
                <thead>
                    <tr>
                        <th>Cédula</th>
                        <th>Aspirante</th>
                        <th>Correo</th>
                        <th>Mención de interés</th>
                        <th>Sede</th>
                        <th>Enviada</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($preinscripciones as $preinscripcion)
                        <tr>
                            <td>{{ $preinscripcion->cedula }}</td>
                            <td>{{ $preinscripcion->apellido }}, {{ $preinscripcion->nombre }}</td>
                            <td>{{ $preinscripcion->correo }}</td>
                            <td>
                                @if ($preinscripcion->mencion)
                                    {{ $preinscripcion->mencion->nombre }}
                                    <small style="color: #9fb3d1;">
                                        ({{ $preinscripcion->mencion->postgrado?->nombre ?? 'Sin postgrado' }})
                                    </small>
                                @else
                                    Sin mención
                                @endif
                            </td>
                            <td>{{ $preinscripcion->sede }}</td>
                            <td>{{ $preinscripcion->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="estado-prein {{ $preinscripcion->estado }}">
                                    {{ ucfirst($preinscripcion->estado) }}
                                </span>
                            </td>
                            <td>
                                <div class="acciones-prein">
                                    <a href="{{ route('administrador.preinscripciones.descarga', $preinscripcion) }}"
                                        class="btn-table-action" title="Descargar planilla (.docx)">
                                        <i class="fa-solid fa-file-word"></i>
                                    </a>

                                    @if ($preinscripcion->estado !== 'aprobada')
                                        <form method="POST"
                                            action="{{ route('administrador.preinscripciones.aprobar', $preinscripcion) }}">
                                            @csrf
                                            <button type="submit" class="btn-table-action"
                                                title="Aprobar preinscripción">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if ($preinscripcion->estado !== 'rechazada')
                                        <form method="POST"
                                            action="{{ route('administrador.preinscripciones.rechazar', $preinscripcion) }}">
                                            @csrf
                                            <button type="submit" class="btn-table-action"
                                                title="Rechazar preinscripción">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 30px;">
                                Todavía no hay preinscripciones registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layout>
