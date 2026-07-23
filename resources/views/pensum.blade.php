<x-layout title='PostUDO || Pensum Académico'>
    <section class="main-content-section page-content">
        <div class="content_texto_bienvenida">
            <label>Mi Pensum Académico</label>
        </div>

        <div id="pensum_container" class="p-4 md:p-8 lg:p-12">
            @php
                // Datos de ejemplo para demostración.
                // En una aplicación real, estos datos vendrían de tu base de datos
                // a través de un controlador y modelos Eloquent.
                $postgrados = [
                    [
                        'nombre' => 'Maestría en Ciencias de la Computación',
                        'cohortes' => [
                            [
                                'anio' => 2023,
                                'semestres' => [
                                    [
                                        'nombre' => 'Primer Semestre',
                                        'materias' => [
                                            [
                                                'codigo' => 'MCC501',
                                                'nombre' => 'Algoritmos Avanzados',
                                                'unidades_credito' => 4,
                                                'secciones' => [
                                                    ['nro_seccion' => 1, 'horario' => 'Lunes 09:00 - 11:00 (Aula C101)'],
                                                    ['nro_seccion' => 2, 'horario' => 'Miércoles 14:00 - 16:00 (Aula C102)'],
                                                ],
                                            ],
                                            [
                                                'codigo' => 'MCC502',
                                                'nombre' => 'Arquitectura de Software',
                                                'unidades_credito' => 3,
                                                'secciones' => [
                                                    ['nro_seccion' => 1, 'horario' => 'Martes 10:00 - 12:00 (Aula C103)'],
                                                ],
                                            ],
                                        ],
                                    ],
                                    [
                                        'nombre' => 'Segundo Semestre',
                                        'materias' => [
                                            [
                                                'codigo' => 'MCC503',
                                                'nombre' => 'Inteligencia Artificial',
                                                'unidades_credito' => 4,
                                                'secciones' => [
                                                    ['nro_seccion' => 1, 'horario' => 'Jueves 09:00 - 11:00 (Aula C101)'],
                                                    ['nro_seccion' => 2, 'horario' => 'Viernes 14:00 - 16:00 (Aula C102)'],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'anio' => 2024,
                                'semestres' => [
                                    [
                                        'nombre' => 'Primer Semestre',
                                        'materias' => [
                                            [
                                                'codigo' => 'MCC601',
                                                'nombre' => 'Análisis de Datos',
                                                'unidades_credito' => 3,
                                                'secciones' => [
                                                    ['nro_seccion' => 1, 'horario' => 'Lunes 14:00 - 16:00 (Aula C201)'],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'nombre' => 'Especialización en Gerencia Pública',
                        'cohortes' => [
                            [
                                'anio' => 2023,
                                'semestres' => [
                                    [
                                        'nombre' => 'Primer Semestre',
                                        'materias' => [
                                            [
                                                'codigo' => 'EGP401',
                                                'nombre' => 'Fundamentos de Administración Pública',
                                                'unidades_credito' => 3,
                                                'secciones' => [
                                                    ['nro_seccion' => 1, 'horario' => 'Martes 08:00 - 10:00 (Aula B205)'],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ];
            @endphp

            @foreach ($postgrados as $postgrado)
                <div class="postgrado-card bg-white shadow-lg rounded-lg p-6 mb-8">
                    <h2 class="text-2xl font-bold text-blue-800 mb-4">{{ $postgrado['nombre'] }}</h2>
                    @if (empty($postgrado['cohortes']))
                        <p class="text-gray-600">No hay cohortes disponibles para este posgrado.</p>
                    @else
                        @foreach ($postgrado['cohortes'] as $cohorte)
                            <div class="cohorte-card bg-blue-50 border border-blue-200 rounded-md p-5 mb-6">
                                <h3 class="text-xl font-semibold text-blue-700 mb-3">Cohorte {{ $cohorte['anio'] }}</h3>
                                @if (empty($cohorte['semestres']))
                                    <p class="text-gray-600">No hay semestres definidos para esta cohorte.</p>
                                @else
                                    @foreach ($cohorte['semestres'] as $semestre)
                                        <div class="semestre-card bg-blue-100 border-l-4 border-blue-500 rounded-sm p-4 mb-5">
                                            <h4 class="text-lg font-medium text-gray-800 mb-3">{{ $semestre['nombre'] }}</h4>
                                            @if (empty($semestre['materias']))
                                                <p class="text-gray-600">No hay materias programadas para este semestre.</p>
                                            @else
                                                <div class="overflow-x-auto">
                                                    <table class="min-w-full bg-white border border-gray-300 rounded-md">
                                                        <thead>
                                                            <tr>
                                                                <th class="py-2 px-4 border-b border-gray-300 bg-gray-100 text-left text-sm font-semibold text-gray-700 rounded-tl-md">Código</th>
                                                                <th class="py-2 px-4 border-b border-gray-300 bg-gray-100 text-left text-sm font-semibold text-gray-700">Materia</th>
                                                                <th class="py-2 px-4 border-b border-gray-300 bg-gray-100 text-center text-sm font-semibold text-gray-700">U.C.</th>
                                                                <th class="py-2 px-4 border-b border-gray-300 bg-gray-100 text-left text-sm font-semibold text-gray-700 rounded-tr-md">Secciones y Horarios</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($semestre['materias'] as $materia)
                                                                <tr class="hover:bg-gray-50">
                                                                    <td class="py-3 px-4 border-b border-gray-200 text-sm text-gray-700">{{ $materia['codigo'] }}</td>
                                                                    <td class="py-3 px-4 border-b border-gray-200 text-sm text-gray-700">{{ $materia['nombre'] }}</td>
                                                                    <td class="py-3 px-4 border-b border-gray-200 text-sm text-gray-700 text-center">{{ $materia['unidades_credito'] }}</td>
                                                                    <td class="py-3 px-4 border-b border-gray-200 text-sm text-gray-700">
                                                                        @if (empty($materia['secciones']))
                                                                            <span class="text-gray-500 italic">Sin secciones</span>
                                                                        @else
                                                                            <ul class="list-disc list-inside space-y-1">
                                                                                @foreach ($materia['secciones'] as $seccion)
                                                                                    <li>
                                                                                        <strong class="text-gray-700">Sección {{ $seccion['nro_seccion'] }}:</strong> {{ $seccion['horario'] }}
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            @endforeach
        </div>
    </section>
</x-layout>
