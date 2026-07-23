<x-layout title='PostUDO || Mi Horario Académico'>
    <section class="main-content-section page-content">
        <div class="content_texto_bienvenida">
            <label>Mi Horario Académico</label>
        </div>

        {{-- El contenedor del horario ahora es visible directamente --}}
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
                            // Horas de ejemplo. Ajusta esto a las horas reales de tus clases.
                            $horas = [
                                '07:00 - 07:45', '07:45 - 08:30', '08:30 - 09:15', '09:15 - 10:00',
                                '10:00 - 10:45', '10:45 - 11:30', '11:30 - 12:15', '12:15 - 13:00',
                                '13:00 - 13:45', '13:45 - 14:30', '14:30 - 15:15', '15:15 - 16:00',
                            ];

                            // Datos de horario simulados para el profesor actual.
                            // En una aplicación real, esto se cargaría directamente desde el backend
                            // con el horario del profesor autenticado.
                            $horario_ejemplo = [
                                '07:00 - 07:45' => [
                                    'Lunes' => 'Programación Avanzada (Sección A)',
                                    'Martes' => 'Matemática III (Sección B)',
                                    'Miércoles' => '',
                                    'Jueves' => 'Inglés Técnico (Sección A)',
                                    'Viernes' => 'Física II (Sección C)',
                                ],
                                '07:45 - 08:30' => [
                                    'Lunes' => 'Programación Avanzada (Sección A)',
                                    'Martes' => 'Matemática III (Sección B)',
                                    'Miércoles' => '',
                                    'Jueves' => 'Inglés Técnico (Sección A)',
                                    'Viernes' => 'Física II (Sección C)',
                                ],
                                '08:30 - 09:15' => [
                                    'Lunes' => '',
                                    'Martes' => '',
                                    'Miércoles' => 'Electrónica Digital (Sección B)',
                                    'Jueves' => '',
                                    'Viernes' => '',
                                ],
                                '09:15 - 10:00' => [
                                    'Lunes' => '',
                                    'Martes' => '',
                                    'Miércoles' => 'Electrónica Digital (Sección B)',
                                    'Jueves' => '',
                                    'Viernes' => '',
                                ],
                                '10:00 - 10:45' => [
                                    'Lunes' => 'Física II (Sección A)',
                                    'Martes' => 'Programación Avanzada (Sección B)',
                                    'Miércoles' => '',
                                    'Jueves' => 'Matemática III (Sección C)',
                                    'Viernes' => '',
                                ],
                                '10:45 - 11:30' => [
                                    'Lunes' => 'Física II (Sección A)',
                                    'Martes' => 'Programación Avanzada (Sección B)',
                                    'Miércoles' => '',
                                    'Jueves' => 'Matemática III (Sección C)',
                                    'Viernes' => '',
                                ],
                                '11:30 - 12:15' => [
                                    'Lunes' => '',
                                    'Martes' => '',
                                    'Miércoles' => 'Diseño Web (Sección A)',
                                    'Jueves' => '',
                                    'Viernes' => 'Seminario de Tesis (Sección Única)',
                                ],
                                '12:15 - 13:00' => [
                                    'Lunes' => '',
                                    'Martes' => '',
                                    'Miércoles' => 'Diseño Web (Sección A)',
                                    'Jueves' => '',
                                    'Viernes' => 'Seminario de Tesis (Sección Única)',
                                ],
                                '13:00 - 13:45' => [
                                    'Lunes' => 'Redes de Computadoras (Sección C)',
                                    'Martes' => '',
                                    'Miércoles' => '',
                                    'Jueves' => 'Base de Datos I (Sección B)',
                                    'Viernes' => '',
                                ],
                                '13:45 - 14:30' => [
                                    'Lunes' => 'Redes de Computadoras (Sección C)',
                                    'Martes' => '',
                                    'Miércoles' => '',
                                    'Jueves' => 'Base de Datos I (Sección B)',
                                    'Viernes' => '',
                                ],
                                '14:30 - 15:15' => [
                                    'Lunes' => '',
                                    'Martes' => 'Inteligencia Artificial (Sección A)',
                                    'Miércoles' => '',
                                    'Jueves' => '',
                                    'Viernes' => '',
                                ],
                                '15:15 - 16:00' => [
                                    'Lunes' => '',
                                    'Martes' => 'Inteligencia Artificial (Sección A)',
                                    'Miércoles' => '',
                                    'Jueves' => '',
                                    'Viernes' => '',
                                ],
                            ];
                        @endphp

                        @foreach ($horas as $hora)
                            <tr>
                                <td style="font-weight: bold; text-align: left;">{{ $hora }}</td>
                                @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'] as $dia)
                                    <td style="padding: 10px; border: 1px solid #eee; text-align: center; vertical-align: middle;">
                                        {{ $horario_ejemplo[$hora][$dia] ?? '' }}
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
