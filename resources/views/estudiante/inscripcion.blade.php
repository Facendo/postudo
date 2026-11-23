<x-layout title='PostUDO || Mi Inscripción'>
    <style>
        /* Estilos locales de la consulta de inscripción (solo esta página) */
        .estado-insc {
            display: inline-block;
            padding: 5px 16px;
            border-radius: 14px;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            margin-bottom: 14px;
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

        .texto-estado {
            font-size: 0.92rem;
            line-height: 1.6;
            color: var(--texto-oscuro, #333);
            text-align: center;
        }

        .ficha-inscripcion {
            margin-top: 18px;
            padding: 14px 18px;
            border: 1px solid var(--borde-suave, rgba(0, 0, 0, 0.1));
            border-radius: 10px;
            background: rgba(0, 0, 0, 0.03);
            text-align: left;
            font-size: 0.88rem;
            line-height: 1.8;
        }

        .ficha-inscripcion p {
            margin: 0;
        }
    </style>

    <section class="login-page-container">
        <div class="registration-form">
            <h2 class="registration-form-title">Estado de mi Inscripción</h2>

            @php
                // Mención/postgrado a mostrar: los del estudiante; si no
                // tiene fila (o le faltan), los de la cohorte asignada.
                $mencion = $estudiante?->mencion ?? $inscripcion?->cohorte?->mencion;
                $postgrado = $mencion?->postgrado ?? $inscripcion?->cohorte?->postgrado;
            @endphp

            @if ($inscripcion === null)
                {{-- Sin fila en 'inscripcion' = sin proceso gestionado aún --}}
                <div style="text-align: center;">
                    <span class="estado-insc pendiente">Sin proceso</span>
                    <p class="texto-estado">
                        Aún no tienes un proceso de inscripción.
                        @if ($estudiante)
                            Si acabas de enviar tu preinscripción, espera a que la coordinación
                            de tu postgrado la revise; el coordinador es quien te inscribe en
                            una cohorte.
                        @else
                            Contacta con la coordinación de tu postgrado para más información.
                        @endif
                    </p>
                </div>
            @elseif ($inscripcion->estado === 'inscrita')
                <div style="text-align: center;">
                    <span class="estado-insc inscrita">Inscrita</span>
                    <p class="texto-estado">
                        Estás inscrito(a) en la cohorte
                        <strong>{{ $inscripcion->codigo_cohorte }}</strong>
                        @if ($inscripcion->cohorte?->fecha_inicio)
                            (desde el {{ \Carbon\Carbon::parse($inscripcion->cohorte->fecha_inicio)->format('d/m/Y') }}
                            @if ($inscripcion->cohorte?->fecha_fin)
                                hasta el {{ \Carbon\Carbon::parse($inscripcion->cohorte->fecha_fin)->format('d/m/Y') }}
                            @endif)
                        @endif
                        @if ($mencion)
                            de la mención <strong>{{ $mencion->nombre }}</strong>
                            @if ($postgrado)
                                del postgrado <strong>{{ $postgrado->nombre }}</strong>
                            @endif
                        @endif
                        .
                    </p>
                </div>
            @elseif ($inscripcion->estado === 'aprobada')
                <div style="text-align: center;">
                    <span class="estado-insc aprobada">Aprobada</span>
                    <p class="texto-estado">
                        Tu preinscripción fue aprobada. El coordinador de tu postgrado
                        te inscribirá en una cohorte de tu mención; vuelve a consultar
                        para ver el detalle cuando esté completa.
                    </p>
                </div>
            @elseif ($inscripcion->estado === 'rechazada')
                <div style="text-align: center;">
                    <span class="estado-insc rechazada">Rechazada</span>
                    <p class="texto-estado">
                        Tu inscripción fue rechazada por la coordinación de tu postgrado.
                        Si tienes dudas, contacta directamente con ella.
                    </p>
                </div>
            @endif

            @if ($estudiante)
                <div class="ficha-inscripcion">
                    <p><strong>Estudiante:</strong>
                        {{ trim(($estudiante->nombre ?? '').' '.($estudiante->apellido ?? '')) }}</p>
                    <p><strong>Mención:</strong>
                        {{ $mencion?->nombre ?? 'Sin mención asignada' }}</p>
                    <p><strong>Postgrado:</strong>
                        {{ $postgrado?->nombre ?? '—' }}</p>
                </div>
            @endif
        </div>
    </section>
</x-layout>
