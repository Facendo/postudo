<x-layout title='PostUDO || Mi Historial Académico'>
    <section class="main-content-section page-content">
        <div class="content_texto_bienvenida">
            <label>Mi Historial Académico por Cohorte</label>
        </div>

        <div class="academic-history-container" style="margin-top: 20px;">
            @php
                // Simulación de las cohortes en las que el estudiante ha estado inscrito
                // En una aplicación real, esto provendría del backend (ej. $estudiante->cohortes)
                $cohortes_inscritas_ejemplo = [
                    ['codigo_cohorte' => 'C-2023-INF-01', 'nro_de_cohorte' => 1],
                    ['codigo_cohorte' => 'C-2024-CIV-02', 'nro_de_cohorte' => 2],
                    ['codigo_cohorte' => 'C-2024-INF-03', 'nro_de_cohorte' => 3],
                ];

                // Simulación de las materias cursadas y sus notas finales por cohorte
                // En una aplicación real, esto provendría del backend,
                // quizás a través de una relación de pivote o una consulta específica
                $materias_cursadas_por_cohorte_ejemplo = [
                    'C-2023-INF-01' => [
                        ['nombre_materia' => 'Introducción a la Programación', 'nota_final' => 8],
                        ['nombre_materia' => 'Cálculo I', 'nota_final' => 7],
                        ['nombre_materia' => 'Física General', 'nota_final' => 9],
                    ],
                    'C-2024-INF-02' => [
                        ['nombre_materia' => 'Estática', 'nota_final' => 7],
                        ['nombre_materia' => 'Topografía', 'nota_final' => 6],
                    ],
                    'C-2024-INF-03' => [
                        ['nombre_materia' => 'Algoritmos y Estructuras de Datos', 'nota_final' => 9],
                        ['nombre_materia' => 'Bases de Datos I', 'nota_final' => 8],
                        ['nombre_materia' => 'Redes de Computadoras', 'nota_final' => 7],
                    ],
                ];
            @endphp

            @forelse ($cohortes_inscritas_ejemplo as $cohorte)
                <div class="cohorte-section" style="margin-bottom: 30px; padding: 20px; border: 1px solid #e0e0e0; border-radius: 8px; background-color: #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <h3 style="margin-top: 0; margin-bottom: 15px; color: #333; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                        Cohorte N° {{ $cohorte['nro_de_cohorte'] }} ({{ $cohorte['codigo_cohorte'] }})
                    </h3>

                    @php
                        $materias_de_esta_cohorte = $materias_cursadas_por_cohorte_ejemplo[$cohorte['codigo_cohorte']] ?? [];
                    @endphp

                    @if (empty($materias_de_esta_cohorte))
                        <p style="text-align: center; color: #777; padding: 15px;">No hay materias registradas para esta cohorte.</p>
                    @else
                        <div class="table-container" style="overflow-x: auto;">
                            <table style="min-width: 500px; border-collapse: collapse; width: 100%;">
                                <thead>
                                    <tr>
                                        {{-- Cambiado el color de fondo a naranja (#FFA500) --}}
                                        <th style="width: 70%; text-align: left; padding: 12px; background-color: #FFA500; border-bottom: 1px solid #ddd;">Materia</th>
                                        <th style="width: 30%; text-align: center; padding: 12px; background-color: #FFA500; border-bottom: 1px solid #ddd;">Nota Final</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($materias_de_esta_cohorte as $materia)
                                        <tr>
                                            <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $materia['nombre_materia'] }}</td>
                                            <td style="padding: 10px; border-bottom: 1px solid #eee; text-align: center;">{{ number_format($materia['nota_final'], 1) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @empty
                <p style="text-align: center; font-size: 1.1em; color: #555; padding: 30px; border: 1px dashed #ccc; border-radius: 8px;">
                    El estudiante no está inscrito en ninguna cohorte o aún no ha cursado materias.
                </p>
            @endforelse
        </div>
    </section>
</x-layout>
