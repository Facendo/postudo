<x-layout title='PostUDO || Gestión de Estudiantes'>
    @php
        // El coordinador de postgrado tiene el listado en SOLO LECTURA
        // (2026-11-23): las rutas de editar/eliminar estudiantes son del
        // coordinador general, así que sus botones no se pintan (el alta
        // directa se retiró ese mismo día: ya no hay «+ Nuevo Estudiante»).
        $conAcciones = Auth::user()->rol === 'coordinador_general';
    @endphp

    <div class="page-content"
        style="width: 90%; max-width: 1200px; margin: 40px auto; display: flex; flex-direction: column; align-items: center;">

        {{-- Título de la sección (patrón de Asuntos/Preinscripciones) --}}
        <div class="content_texto_bienvenida">
            <label>Listado de Estudiantes</label>
        </div>

        {{-- Sin alta de estudiantes: el registro directo se retiró
             (2026-11-23), así que ya no hay botón «+ Nuevo Estudiante».
             Solo queda el subtítulo informativo para el coordinador de
             postgrado. --}}
        @if (Auth::user()->rol === 'coordinador_postgrado')
            <div style="margin: 10px 0 5px; color: var(--texto-suave); font-size: 0.92rem;">
                Estudiantes asociados a tus postgrados asignados.
            </div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>Cédula</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Postgrado</th>
                    <th>Mención</th>
                    <th>Correo</th>
                    <th>Edad</th>
                    @if ($conAcciones)
                        <th style="text-align: center;">Acciones</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($estudiantes as $estudiante)
                    <tr>
                        <td>{{ $estudiante->cedula }}</td>
                        <td>{{ $estudiante->nombre }}</td>
                        <td>{{ $estudiante->apellido }}</td>
                        <td>{{ $estudiante->carrera }}</td>
                        <td>{{ $estudiante->especialidad }}</td>
                        <td>{{ $estudiante->correo }}</td>
                        <td>{{ $estudiante->edad }}</td>

                        @if ($conAcciones)
                            <td style="text-align: center;">
                                <div class="acciones-est">
                                    <a href="{{ route('administrador.gestion_estudiante.edit', $estudiante) }}"
                                        class="btn-table-action" title="Editar">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>

                                    <form action="{{ route('administrador.gestion_estudiante.destroy', $estudiante) }}"
                                        method="POST"
                                        onsubmit="return confirm('¿Está seguro de eliminar este estudiante?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-table-action" title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $conAcciones ? 8 : 7 }}" style="text-align: center; padding: 30px;">
                            @if (isset($sinPostgradosAsignados) && $sinPostgradosAsignados)
                                No tienes postgrados asignados: contacta con el coordinador general
                                para que te asigne uno o actualice tu cuenta.
                            @elseif (Auth::user()->rol === 'coordinador_postgrado')
                                No hay estudiantes en tus postgrados.
                            @else
                                No hay estudiantes registrados.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <style>
        /* Acciones en línea dentro de la celda (mismo patrón que
           preinscripciones): el <form> por defecto es bloque y dejaría el
           botón de eliminar debajo del de editar. */
        .acciones-est {
            display: inline-flex;
            gap: 6px;
            align-items: center;
        }

        .acciones-est form {
            display: inline;
        }

        .acciones-est button {
            border: none;
            cursor: pointer;
            font: inherit;
        }
    </style>
</x-layout>
