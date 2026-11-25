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
            color: var(--accent-orange-dark);
        }

        .estado-prein.aprobada {
            background: rgba(40, 167, 69, 0.15);
            border: 1px solid rgba(40, 167, 69, 0.45);
            color: #15803d;
        }

        .estado-prein.rechazada {
            background: rgba(220, 53, 69, 0.15);
            border: 1px solid rgba(220, 53, 69, 0.45);
            color: var(--danger-red);
        }

        /* Estado del pago del voucher (columna «Pago») */
        .pago-prein {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 14px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .pago-prein.sin-registro {
            background: rgba(108, 117, 125, 0.15);
            border: 1px solid rgba(108, 117, 125, 0.45);
            color: var(--texto-suave);
        }

        .pago-prein.pendiente {
            background: rgba(247, 148, 29, 0.15);
            border: 1px solid rgba(247, 148, 29, 0.45);
            color: var(--accent-orange-dark);
        }

        .pago-prein.verificado {
            background: rgba(40, 167, 69, 0.15);
            border: 1px solid rgba(40, 167, 69, 0.45);
            color: #15803d;
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

        <div style="margin: 10px 0 25px; color: var(--texto-suave); font-size: 0.92rem; text-align: center;">
            @if (Auth::user()->rol === 'coordinador_postgrado')
                Aprobar una preinscripción la envía al apartado de
                <a href="{{ route('coordinador.inscripciones.index') }}"
                    style="color: var(--primary-blue); font-weight: 600;">Inscripciones</a>,
                donde apruebas o rechazas al aspirante y lo inscribes en una cohorte de su mención.
            @else
                Aprobar una preinscripción habilita al aspirante para su inscripción.
            @endif
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
                        <th>Pago</th>
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
                                    <small style="color: var(--texto-suave);">
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
                                {{-- Pago del voucher creado junto con la preinscripción. --}}
                                @php($pagoVoucher = $pagosVoucher->get($preinscripcion->cedula))
                                @if (! $pagoVoucher)
                                    <span class="pago-prein sin-registro">Sin registro</span>
                                @elseif ($pagoVoucher->estado === 'Actualizado')
                                    <span class="pago-prein verificado">Verificado</span>
                                @else
                                    <span class="pago-prein pendiente">Pendiente</span>
                                @endif
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
                                @if ($sinPostgradosAsignados)
                                    No tienes postgrados asignados: contacta con el coordinador general
                                    para que te asigne uno o actualice tu cuenta.
                                @elseif (Auth::user()->rol === 'coordinador_postgrado')
                                    Todavía no hay preinscripciones registradas para tus postgrados.
                                @else
                                    Todavía no hay preinscripciones registradas.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layout>
