<x-layout title="PostUDO || Inscripciones">
    <style>
        /* Estilos locales del apartado de inscripciones (solo esta página) */
        .estado-insc {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 14px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .estado-insc.pendiente {
            background: rgba(247, 148, 29, 0.15);
            border: 1px solid rgba(247, 148, 29, 0.45);
            color: var(--accent-orange-dark);
        }

        .estado-insc.aprobada {
            background: rgba(0, 61, 165, 0.12);
            border: 1px solid rgba(0, 61, 165, 0.4);
            color: var(--primary-blue);
        }

        .estado-insc.rechazada {
            background: rgba(220, 53, 69, 0.15);
            border: 1px solid rgba(220, 53, 69, 0.45);
            color: var(--danger-red);
        }

        .estado-insc.inscrita {
            background: rgba(40, 167, 69, 0.15);
            border: 1px solid rgba(40, 167, 69, 0.45);
            color: #15803d;
        }

        .acciones-insc {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .acciones-insc form {
            display: inline;
        }

        .acciones-insc button {
            border: none;
            cursor: pointer;
            font: inherit;
        }

        /* Select del paso 2 (elegir cohorte) dentro de la fila */
        .select-cohorte {
            padding: 7px 10px;
            font-size: 0.85rem;
            max-width: 240px;
        }

        .cohorte-asignada {
            display: block;
            margin-top: 5px;
            font-size: 0.8rem;
            color: var(--texto-suave);
        }
    </style>

    <div class="page-content"
        style="width: 92%; max-width: 1250px; margin: 40px auto; display: flex; flex-direction: column; align-items: center;">

        <div class="content_texto_bienvenida">
            <label>Inscripciones</label>
        </div>

        <div style="margin: 10px 0 25px; color: var(--texto-suave); font-size: 0.92rem; text-align: center;">
            Aspirantes con preinscripción aprobada. Primero aprueba o rechaza al aspirante
            (al aprobar se crea su ficha de estudiante) y luego inscríbelo en una cohorte
            de su mención.
            <a href="{{ route('administrador.preinscripciones.index') }}" style="color: var(--primary-blue); font-weight: 600;">
                ← Ver Preinscripciones
            </a>
        </div>

        <div class="contenedor-tabla">
            <table>
                <thead>
                    <tr>
                        <th>Cédula</th>
                        <th>Aspirante</th>
                        <th>Correo</th>
                        <th>Mención</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($preinscripciones as $preinscripcion)
                        @php
                            // Estado derivado: sin fila en 'inscripcion' = pendiente.
                            $estado = $preinscripcion->inscripcion?->estado ?? 'pendiente';
                            // Solo cohortes de la mención del aspirante (paso 2).
                            $cohortesDeLaMencion = $cohortes->where(
                                'codigo_mencion',
                                $preinscripcion->codigo_mencion
                            );
                        @endphp
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
                            <td>
                                <span class="estado-insc {{ $estado }}">{{ ucfirst($estado) }}</span>
                                @if ($estado === 'inscrita')
                                    <span class="cohorte-asignada">
                                        Cohorte {{ $preinscripcion->inscripcion->codigo_cohorte }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="acciones-insc">
                                    @if ($estado === 'pendiente')
                                        {{-- Paso 1: aprobar (crea la fila en estudiantes) o rechazar --}}
                                        <form method="POST"
                                            action="{{ route('coordinador.inscripciones.aprobar', $preinscripcion) }}"
                                            onsubmit="return confirm('¿Aprobar al aspirante? Se creará su ficha de estudiante.');">
                                            @csrf
                                            <button type="submit" class="btn-table-action"
                                                title="Aprobar aspirante">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>

                                        <form method="POST"
                                            action="{{ route('coordinador.inscripciones.rechazar', $preinscripcion) }}">
                                            @csrf
                                            <button type="submit" class="btn-table-action"
                                                title="Rechazar aspirante">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </form>
                                    @elseif ($estado === 'aprobada')
                                        {{-- Paso 2: inscribir en una cohorte de SU mención --}}
                                        @if ($cohortesDeLaMencion->isEmpty())
                                            <small style="color: var(--danger-red);">
                                                Sin cohortes para su mención
                                            </small>
                                        @else
                                            <form method="POST"
                                                action="{{ route('coordinador.inscripciones.inscribir', $preinscripcion) }}"
                                                onsubmit="return confirm('¿Inscribir al aspirante en la cohorte elegida?');">
                                                @csrf
                                                <select name="codigo_cohorte" class="form-input select-cohorte"
                                                    required>
                                                    <option value="">Elegir cohorte…</option>
                                                    @foreach ($cohortesDeLaMencion as $cohorte)
                                                        <option value="{{ $cohorte->codigo_cohorte }}">
                                                            {{ $cohorte->codigo_cohorte }}
                                                            — desde
                                                            {{ \Carbon\Carbon::parse($cohorte->fecha_inicio)->format('d/m/Y') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn-table-action"
                                                    title="Inscribir en la cohorte elegida">
                                                    <i class="fa-solid fa-user-graduate"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @elseif ($estado === 'rechazada')
                                        {{-- Reconsideración: vuelve a pendiente de decisión --}}
                                        <form method="POST"
                                            action="{{ route('coordinador.inscripciones.aprobar', $preinscripcion) }}"
                                            onsubmit="return confirm('¿Aprobar al aspirante? Se creará su ficha de estudiante.');">
                                            @csrf
                                            <button type="submit" class="btn-table-action"
                                                title="Aprobar aspirante (reconsiderar)">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>
                                    @else
                                        <small style="color: var(--texto-suave);">Completado</small>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px;">
                                @if ($sinPostgradosAsignados)
                                    No tienes postgrados asignados: contacta con el coordinador general
                                    para que te asigne uno o actualice tu cuenta.
                                @else
                                    No hay preinscripciones aprobadas para tus postgrados.
                                    <a href="{{ route('administrador.preinscripciones.index') }}"
                                        style="color: var(--primary-blue); font-weight: 600;">
                                        Aprueba una desde Preinscripciones
                                    </a>.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layout>
