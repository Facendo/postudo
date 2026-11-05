<x-layout title='PostUDO || Notificaciones'>

    <div class="page-content notificaciones-pagina"
        style="width: 90%; max-width: 1100px; margin: 40px auto; display: flex; flex-direction: column; align-items: center;">

        {{-- Título de la sección --}}
        <div class="content_texto_bienvenida">
            <label>Notificaciones</label>
        </div>

        {{-- Toolbar: contador + acciones --}}
        <div class="notificaciones-toolbar">
            @if ($noLeidas > 0)
                <span class="notificaciones-contador notificaciones-contador--nuevas">
                    <i class="fa-solid fa-bell"></i>
                    {{ $noLeidas }} sin leer
                </span>
            @else
                <span class="notificaciones-contador">
                    <i class="fa-regular fa-bell-slash"></i>
                    Todo está leído
                </span>
            @endif

            <div class="notificaciones-acciones-toolbar">
                @if ($noLeidas > 0)
                    <form action="{{ route('notificacion.leer_todas') }}" method="POST" class="inline-form">
                        @csrf
                        <button type="submit" class="button_body">
                            <i class="fa-solid fa-check-double icon-left"></i> Marcar todas como leídas
                        </button>
                    </form>
                @endif

                @if (in_array(Auth::user()->rol, ['administrador', 'coordinador_general']))
                    <a href="{{ route('notificacion.comunicar') }}" class="button_body">
                        <i class="fa-solid fa-paper-plane icon-left"></i> Nuevo comunicado
                    </a>
                @endif
            </div>
        </div>

        {{-- Listado de notificaciones --}}
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 40%;">Notificación</th>
                    <th style="text-align: center;">Tipo</th>
                    <th style="text-align: center;">Fecha</th>
                    <th style="text-align: center;">Estado</th>
                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($notificaciones as $notificacion)
                    <tr class="{{ $notificacion->leida_en ? '' : 'notificacion-fila--nueva' }}">
                        <td>
                            <div class="notificacion-titulo">
                                @if (! $notificacion->leida_en)
                                    <span class="notificacion-punto" title="Sin leer"></span>
                                @endif
                                {{ $notificacion->titulo }}
                            </div>
                            <div class="notificacion-mensaje">{{ $notificacion->mensaje }}</div>
                        </td>
                        <td style="text-align: center;">
                            @php
                                $etiquetas = [
                                    'solicitud' => ['Solicitud', 'fa-file-invoice-dollar'],
                                    'estado' => ['Estado', 'fa-circle-check'],
                                    'comunicado' => ['Comunicado', 'fa-bullhorn'],
                                ];
                                [$etiqueta, $icono] = $etiquetas[$notificacion->tipo] ?? ['Aviso', 'fa-bell'];
                            @endphp
                            <span class="notificacion-tipo notificacion-tipo--{{ $notificacion->tipo }}">
                                <i class="fa-solid {{ $icono }}"></i> {{ $etiqueta }}
                            </span>
                        </td>
                        <td style="text-align: center; white-space: nowrap;">
                            {{ $notificacion->created_at ? $notificacion->created_at->format('d/m/Y H:i') : '—' }}
                        </td>
                        <td style="text-align: center;">
                            @if ($notificacion->leida_en)
                                <span class="notificacion-estado"><i class="fa-solid fa-check"></i> Leída</span>
                            @else
                                <span class="notificacion-estado notificacion-estado--nueva">Sin leer</span>
                            @endif
                        </td>
                        <td class="table-actions" style="text-align: center; white-space: nowrap;">
                            @if ($notificacion->enlace)
                                <a href="{{ $notificacion->enlace }}" class="btn-table-action" title="Ir al detalle">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            @endif

                            @unless ($notificacion->leida_en)
                                <form action="{{ route('notificacion.leer', $notificacion->id) }}" method="POST"
                                    class="inline-form">
                                    @csrf
                                    <button type="submit" class="btn-table-action" title="Marcar como leída">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 24px;">
                            No tienes notificaciones por ahora.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Paginación propia (sin Tailwind: este pipeline usa style.css) --}}
        @if ($notificaciones->hasPages())
            <div class="notificaciones-paginacion">
                @if ($notificaciones->onFirstPage())
                    <span class="pagina-boton pagina-boton--inactivo">Anterior</span>
                @else
                    <a href="{{ $notificaciones->previousPageUrl() }}" class="pagina-boton">Anterior</a>
                @endif

                <span class="pagina-actual">
                    Página {{ $notificaciones->currentPage() }} de {{ $notificaciones->lastPage() }}
                </span>

                @if ($notificaciones->hasMorePages())
                    <a href="{{ $notificaciones->nextPageUrl() }}" class="pagina-boton">Siguiente</a>
                @else
                    <span class="pagina-boton pagina-boton--inactivo">Siguiente</span>
                @endif
            </div>
        @endif
    </div>

</x-layout>
