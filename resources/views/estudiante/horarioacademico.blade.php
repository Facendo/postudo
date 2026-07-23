<x-layout title='PostUDO || Mi Horario Académico'>
    <section class="main-content-section page-content">
        <div class="content_texto_bienvenida">
            <label>Mi Horario Académico</label>
        </div>

        {{-- El contenedor del horario es visible directamente --}}
        <div id="horario_container">
            <div class="table-container" style="overflow-x: auto;">
                <table style="min-width: 700px; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="width: 120px; text-align: center;">Hora</th>
                            <th style="text-align: center;">Lunes</th>
                            <th style="text-align: center;">Martes</th>
                            <th style="text-align: center;">Miércoles</th>
                            <th style="text-align: center;">Jueves</th>
                            <th style="text-align: center;">Viernes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Mapeo de códigos de materia a nombres de materias (ejemplo)
                            $materias_map = [
                                '101' => 'Programación Avanzada',
                                '102' => 'Matemática III',
                                '103' => 'Redes de Datos',
                                // Agrega más mapeos según tus materias
                            ];

                            // Horas de clase estándar para la tabla (puedes ajustar esto)
                            $horas_disponibles = [
                                '07:00 - 08:00', '08:00 - 09:00', '09:00 - 10:00', '10:00 - 11:00',
                                '11:00 - 12:00', '12:00 - 13:00', '13:00 - 14:00', '14:00 - 15:00',
                                '15:00 - 16:00', '16:00 - 17:00', '17:00 - 18:00',
                            ];

                            // Inicializar el horario vacío
                            $horario_estudiante = [];
                            foreach ($horas_disponibles as $hora_slot) {
                                $horario_estudiante[$hora_slot] = array_fill_keys(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'], '');
                            }

                            // Datos de secciones proporcionados por el usuario (simulando que provienen del backend)
                            // En tu controlador, esto sería algo como $secciones_inscritas = $estudiante->secciones;
                            $secciones_inscritas = [
                                [
                                    'nro_seccion' => 2,
                                    'codigo_materia' => 101,
                                    'hora_inicio' => '2024-01-18 08:00:00', // Jueves
                                    'hora_fin' => '2024-01-18 10:00:00',
                                    'cedula_docente' => '12345678', // No se usa directamente aquí, pero es parte del modelo
                                    'aula' => 'A104',
                                    'cupo_maximo' => 30,
                                    'cupo_actual' => 0,
                                ],
                                [
                                    'nro_seccion' => 3,
                                    'codigo_materia' => 102,
                                    'hora_inicio' => '2024-01-20 08:00:00', // Sábado - No se mostrará en L-V
                                    'hora_fin' => '2024-01-20 10:00:00',
                                    'cedula_docente' => '12345678',
                                    'aula' => 'A102',
                                    'cupo_maximo' => 30,
                                    'cupo_actual' => 0,
                                ],
                                // Puedes añadir más secciones de ejemplo aquí para ver cómo se renderizan
                                [
                                    'nro_seccion' => 1,
                                    'codigo_materia' => 103,
                                    'hora_inicio' => '2024-01-15 09:00:00', // Lunes
                                    'hora_fin' => '2024-01-15 11:00:00',
                                    'cedula_docente' => '87654321',
                                    'aula' => 'B201',
                                    'cupo_maximo' => 25,
                                    'cupo_actual' => 15,
                                ],
                                [
                                    'nro_seccion' => 4,
                                    'codigo_materia' => 101,
                                    'hora_inicio' => '2024-01-16 14:00:00', // Martes
                                    'hora_fin' => '2024-01-16 16:00:00',
                                    'cedula_docente' => '12345678',
                                    'aula' => 'C303',
                                    'cupo_maximo' => 28,
                                    'cupo_actual' => 10,
                                ],
                            ];

                            // Función para obtener el día de la semana en español
                            function getDiaDeLaSemana($dateString) {
                                $date = new DateTime($dateString);
                                $formatter = new IntlDateFormatter(
                                    'es_ES',
                                    IntlDateFormatter::FULL,
                                    IntlDateFormatter::FULL,
                                    'America/Caracas', // Ajusta la zona horaria si es necesario
                                    IntlDateFormatter::GREGORIAN,
                                    'EEEE' // Formato para el nombre completo del día de la semana
                                );
                                return ucfirst($formatter->format($date));
                            }

                            // Llenar el horario con las secciones inscritas
                            foreach ($secciones_inscritas as $seccion) {
                                $dia = getDiaDeLaSemana($seccion['hora_inicio']);
                                $hora_inicio = (new DateTime($seccion['hora_inicio']))->format('H:i');
                                $hora_fin = (new DateTime($seccion['hora_fin']))->format('H:i');
                                $nombre_materia = $materias_map[$seccion['codigo_materia']] ?? 'Materia Desconocida';
                                $detalle_clase = "{$nombre_materia} (Sección {$seccion['nro_seccion']}) [Aula: {$seccion['aula']}]";

                                // Iterar a través de los slots de tiempo que cubre la clase
                                $start_timestamp = strtotime($hora_inicio);
                                $end_timestamp = strtotime($hora_fin);

                                foreach ($horas_disponibles as $hora_slot) {
                                    list($slot_start_str, $slot_end_str) = explode(' - ', $hora_slot);
                                    $slot_start_timestamp = strtotime($slot_start_str);
                                    $slot_end_timestamp = strtotime($slot_end_str);

                                    // Si el slot de la tabla se superpone con la clase
                                    if ($start_timestamp < $slot_end_timestamp && $end_timestamp > $slot_start_timestamp) {
                                        if (isset($horario_estudiante[$hora_slot]) && isset($horario_estudiante[$hora_slot][$dia])) {
                                            // Si ya hay algo en la celda, podrías concatenar o manejar la superposición
                                            if (!empty($horario_estudiante[$hora_slot][$dia])) {
                                                // Manejar superposición (ej: "Clase A / Clase B") o sobrescribir
                                                $horario_estudiante[$hora_slot][$dia] .= " / " . $detalle_clase;
                                            } else {
                                                $horario_estudiante[$hora_slot][$dia] = $detalle_clase;
                                            }
                                        }
                                    }
                                }
                            }
                        @endphp

                        @foreach ($horas_disponibles as $hora)
                            <tr>
                                <td style="font-weight: bold; text-align: left;">{{ $hora }}</td>
                                @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'] as $dia)
                                    <td style="padding: 10px; border: 1px solid #eee; text-align: center; vertical-align: middle;">
                                        {{ $horario_estudiante[$hora][$dia] ?? '' }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</x-layout>
