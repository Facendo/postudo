<?php

namespace Database\Seeders;

use App\Models\Estudiante;
use App\Models\Evaluacion;
use App\Models\Materias;
use App\Models\Seccion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Area;
use App\Models\Asunto;
use App\Models\Carrera;
use App\Models\Cohorte;
use App\Models\Especialidades;
use App\Models\Postgrado;
use App\Models\Profesor;

class RellenoAreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Area::create([
            'codigo' => 1,
            'nombre' => 'Administración',
            'descripcion' => 'Área encargada de la administración general.',
        ]);
        Area::create([
            'codigo' => 2,
            'nombre' => 'Ingeniería',
            'descripcion' => 'Área encargada de la formación en ingeniería.',
        ]);
        Area::create([
            'codigo' => 3,
            'nombre' => 'Ciencias Sociales',
            'descripcion' => 'Área encargada de la formación en ciencias sociales.',
        ]);
        Area::create([
            'codigo' => 4,
            'nombre' => 'Ciencias de la Salud',
            'descripcion' => 'Área encargada de la formación en ciencias de la salud.',
        ]);
        Area::create([
            'codigo' => 5,
            'nombre' => 'Educación',
            'descripcion' => 'Área encargada de la formación en educación.',
        ]);
        Area::create([
            'codigo' => 6,
            'nombre' => 'Arte y Diseño',
            'descripcion' => 'Área encargada de la formación en arte y diseño.',
        ]);
        Area::create([
            'codigo' => 7,
            'nombre' => 'Tecnologías de la Información',
            'descripcion' => 'Área encargada de la formación en tecnologías de la información.',
        ]);

        Carrera::create([
            'id_carrera' => 1,
            'nombre' => 'Ingeniería de Sistemas',
            'codigo_area' => 2,
        ]);
        Carrera::create([
            'id_carrera' => 2,
            'nombre' => 'Administración de Empresas',
            'codigo_area' => 1,
        ]);
        Carrera::create([
            'id_carrera' => 3,
            'nombre' => 'Psicología',
            'codigo_area' => 3,
        ]);
        Carrera::create([
            'id_carrera' => 4,
            'nombre' => 'Medicina',
            'codigo_area' => 4,
        ]);
        Carrera::create([
            'id_carrera' => 5,
            'nombre' => 'Pedagogía',
            'codigo_area' => 5,
        ]);

        Especialidades::create([
            'codigo_especialidad' => 1,
            'nombre' => 'Desarrollo de Software',
            'id_carrera' => 1,
        ]);
        Especialidades::create([
            'codigo_especialidad' => 2,
            'nombre' => 'Gestión Empresarial',
            'id_carrera' => 2,
        ]);
        Especialidades::create([
            'codigo_especialidad' => 3,
            'nombre' => 'Psicología Clínica',
            'id_carrera' => 3,
        ]);
        Especialidades::create([
            'codigo_especialidad' => 4,
            'nombre' => 'Cirugía General',
            'id_carrera' => 4,
        ]);
        Especialidades::create([
            'codigo_especialidad' => 5,
            'nombre' => 'Educación Inicial',
            'id_carrera' => 5,
        ]);

        Estudiante::create([
            'cedula' => '12345678',
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'carrera' => 'Ingeniería de Sistemas',
            'especialidad' => 'Desarrollo de Software',
            'correo' => 'juan.perez@example.com',
            'edad' => 25,
        ]);
        Estudiante::create([
            'cedula' => '87654321',
            'nombre' => 'María',
            'apellido' => 'Gómez',
            'carrera' => 'Administración de Empresas',
            'especialidad' => 'Gestión Empresarial',
            'correo' => 'maria.gomez@example.com',
            'edad' => 27,
        ]);
        Estudiante::create([
            'cedula' => '11223344',
            'nombre' => 'Carlos',
            'apellido' => 'López',
            'carrera' => 'Ingeniería de Sistemas',
            'especialidad' => 'Desarrollo de Software',
            'correo' => 'carlos.lopez@example.com',
            'edad' => 23,
        ]);
        Estudiante::create([
            'cedula' => '44332211',
            'nombre' => 'Ana',
            'apellido' => 'Martínez',
            'carrera' => 'Administración de Empresas',
            'especialidad' => 'Gestión Empresarial',
            'correo' => 'ana.martinez@example.com',
            'edad' => 24,
        ]);
        Estudiante::create([
            'cedula' => '55667788',
            'nombre' => 'Luis',
            'apellido' => 'Hernández',
            'carrera' => 'Ingeniería de Sistemas',
            'especialidad' => 'Desarrollo de Software',
            'correo' => 'luis.hernandez@example.com',
            'edad' => 26,
        ]);

        Postgrado::create([
            'id_postgrado' => 'POST-INF-2024',
            'nombre' => 'Maestría en Ingeniería de Software',
            'descripcion' => 'Programa de posgrado enfocado en el desarrollo avanzado de software.',
            'duracion' => 24,
            'codigo_especialidad' => 1,
            'nro_cohortes' => 3,
        ]);
        Postgrado::create([
            'id_postgrado' => 'POST-ADM-2024',
            'nombre' => 'Maestría en Administración de Empresas',
            'descripcion' => 'Programa de posgrado enfocado en la gestión empresarial.',
            'duracion' => 18,
            'codigo_especialidad' => 2,
            'nro_cohortes' => 2,
        ]);
        Postgrado::create([
            'id_postgrado' => 'POST-PSI-2024',
            'nombre' => 'Maestría en Psicología',
            'descripcion' => 'Programa de posgrado enfocado en la formación avanzada en psicología.',
            'duracion' => 24,
            'codigo_especialidad' => 3,
            'nro_cohortes' => 3,
        ]);

        Profesor::create([
            'cedula' => '30731444',
            'nombre' => 'Jose',
            'apellido' => 'Cova',
            'telefono' => '1234567890',
            'correo' => 'gmail@gmail.com',
            'edad' => 40,
        ]);

        Cohorte::create([
            'codigo_cohorte' => 'COHORTE-INF-2024-1',
            'codigo_postgrado' => 'POST-INF-2024',
            'fecha_inicio' => '2024-01-15',
            'fecha_fin' => '2025-01-15',
            'nro_de_cohorte' => 1,
        ]);
        Cohorte::create([
            'codigo_cohorte' => 'COHORTE-INF-2024-2',
            'codigo_postgrado' => 'POST-INF-2024',
            'fecha_inicio' => '2024-06-01',
            'fecha_fin' => '2025-06-01',
            'nro_de_cohorte' => 2,
        ]);
        Cohorte::create([
            'codigo_cohorte' => 'COHORTE-INF-2024-3',
            'codigo_postgrado' => 'POST-INF-2024',
            'fecha_inicio' => '2024-11-01',
            'fecha_fin' => '2025-11-01',
            'nro_de_cohorte' => 3,
        ]);

        Materias::create([
            'codigo_materia' => 101,
            'codigo_cohorte' => 'COHORTE-INF-2024-1',
            'nombre' => 'Programación Avanzada',
            'prelacion' => null,
        ]);
        Materias::create([
            'codigo_materia' => 102,
            'codigo_cohorte' => 'COHORTE-INF-2024-1',
            'nombre' => 'Estructuras de Datos Avanzadas',
            'prelacion' => null,
        ]);
        Materias::create([
            'codigo_materia' => 103,
            'codigo_cohorte' => 'COHORTE-INF-2024-2',
            'nombre' => 'Bases de Datos Avanzadas',
            'prelacion' => 101,
        ]);
        Materias::create([
            'codigo_materia' => 104,
            'codigo_cohorte' => 'COHORTE-INF-2024-2',
            'nombre' => 'Desarrollo Web Avanzado',
            'prelacion' => 102,
        ]);
        Materias::create([
            'codigo_materia' => 105,
            'codigo_cohorte' => 'COHORTE-INF-2024-3',
            'nombre' => 'Arquitectura de Software',
            'prelacion' => 103,
        ]);

        Seccion::create([
            'nro_seccion' => 1,
            'codigo_materia' => 101,
            'hora_inicio' => '2024-01-15 08:00:00',
            'hora_fin' => '2024-01-15 10:00:00',
            'cedula_docente' => '12345678',
            'aula' => 'A101',
            'cupo_maximo' => 30,
            'cupo_actual' => 0,
        ]);
        Seccion::create([
            'nro_seccion' => 2,
            'codigo_materia' => 101,
            'hora_inicio' => '2024-01-18 08:00:00',
            'hora_fin' => '2024-01-18 10:00:00',
            'cedula_docente' => '12345678',
            'aula' => 'A104',
            'cupo_maximo' => 30,
            'cupo_actual' => 0,
        ]);
        Seccion::create([
            'nro_seccion' => 3,
            'codigo_materia' => 102,
            'hora_inicio' => '2024-01-20 08:00:00',
            'hora_fin' => '2024-01-20 10:00:00',
            'cedula_docente' => '12345678',
            'aula' => 'A102',
            'cupo_maximo' => 30,
            'cupo_actual' => 0,
        ]);
        Seccion::create([
            'nro_seccion' => 4,
            'codigo_materia' => 103,
            'hora_inicio' => '2024-06-01 08:00:00',
            'hora_fin' => '2024-06-01 10:00:00',
            'cedula_docente' => '87654321',
            'aula' => 'B101',
            'cupo_maximo' => 30,
            'cupo_actual' => 0,
        ]);
        Seccion::create([
            'nro_seccion' => 5,
            'codigo_materia' => 104,
            'hora_inicio' => '2024-06-02 08:00:00',
            'hora_fin' => '2024-06-02 10:00:00',
            'cedula_docente' => '87654321',
            'aula' => 'B102',
            'cupo_maximo' => 30,
            'cupo_actual' => 0,
        ]);
        Seccion::create([
            'nro_seccion' => 6,
            'codigo_materia' => 105,
            'hora_inicio' => '2024-11-01 08:00:00',
            'hora_fin' => '2024-11-01 10:00:00',
            'cedula_docente' => '12345678',
            'aula' => 'C101',
            'cupo_maximo' => 30,
            'cupo_actual' => 0,
        ]);
        Profesor::create([
            'cedula' => '35415135',
            'nombre' => 'Ana',
            'apellido' => 'Gómez',
            'telefono' => '9876543210',
            'correo' => 'ana.gomez@example.com',
            'edad' => 35
        ]);
        Profesor::create([
            'cedula' => '35131315',
            'nombre' => 'Carlos',
            'apellido' => 'Pérez',
            'telefono' => '5555555555',
            'correo' => 'carlos.perez@example.com',
            'edad' => 40
        ]);
        Profesor::create([
            'cedula' => '87654321',
            'nombre' => 'María',
            'apellido' => 'López',
            'telefono' => '4444444444',
            'correo' => 'maria.lopez@example.com',
            'edad' => 38
        ]);
        Estudiante::create([
            'cedula' => '123456789',
            'nombre' => 'Juan',
            'apellido' => 'Martínez',
            'carrera' => 'Ingeniería de Sistemas',
            'especialidad' => 'Desarrollo de Software',
            'correo' => 'juan.martinez@example.com',
            'edad' => 22
        ]);
        Estudiante::create([
            'cedula' => '987654321',
            'nombre' => 'Laura',
            'apellido' => 'García',
            'carrera' => 'Administración de Empresas',
            'especialidad' => 'Gestión Empresarial',
            'correo' => 'laura.garcia@example.com',
            'edad' => 21
        ]);
        Estudiante::create([
            'cedula' => '456789123',
            'nombre' => 'Pedro',
            'apellido' => 'Ramírez',
            'carrera' => 'Ingeniería de Software',
            'especialidad' => 'Desarrollo Web',
            'correo' => 'pedro.ramirez@example.com',
            'edad' => 23
        ]);

        Evaluacion::create([
            'codigo_materia' => 101,
            'codigo_seccion' => 1,
            'titulo' => 'Examen Parcial 1',
            'porcentaje' => 30,
            'metodologia' => 'Examen escrito',
            'fecha' => '2024-02-15',
            'nota' => null, // Asegúrate de que este campo sea nullable si es necesario
        ]);
        Evaluacion::create([
            'codigo_materia' => 101,
            'codigo_seccion' => 2,
            'titulo' => 'Examen Parcial 2',
            'porcentaje' => 30,
            'metodologia' => 'Examen escrito',
            'fecha' => '2024-02-15',
            'nota' => null, // Asegúrate de que este campo sea nullable si es necesario
        ]);
        Evaluacion::create([
            'codigo_materia' => 101,
            'codigo_seccion' => 1,
            'titulo' => 'Proyecto Final',
            'porcentaje' => 70,
            'metodologia' => 'Proyecto práctico',
            'fecha' => '2024-03-15',
            'nota' => null, // Asegúrate de que este campo sea nullable si es necesario
        ]);
        Evaluacion::create([
            'codigo_materia' => 101,
            'codigo_seccion' => 2, 
            'titulo' => 'Proyecto Final',
            'porcentaje' => 70,
            'metodologia' => 'Proyecto práctico',
            'fecha' => '2024-03-15',
            'nota' => null, // Asegúrate de que este campo sea nullable si es necesario
        ]);
        Evaluacion::create([
            'codigo_materia' => 102,
            'codigo_seccion' => 3,
            'titulo' => 'Examen Parcial 1',
            'porcentaje' => 30,
            'metodologia' => 'Examen escrito',
            'fecha' => '2024-02-15',
            'nota' => null, // Asegúrate de que este campo sea nullable si es necesario
        ]);
        Evaluacion::create([
            'codigo_materia' => 102,
            'codigo_seccion' => 3,
            'titulo' => 'Proyecto Final',
            'porcentaje' => 70,
            'metodologia' => 'Proyecto práctico',
            'fecha' => '2024-03-15',
            'nota' => null, // Asegúrate de que este campo sea nullable si es necesario
        ]);
        Evaluacion::create([
            'codigo_materia' => 103,
            'codigo_seccion' => 4,
            'titulo' => 'Examen Parcial 1',
            'porcentaje' => 30,
            'metodologia' => 'Examen escrito',
            'fecha' => '2024-02-15',
            'nota' => null, // Asegúrate de que este campo sea nullable si es necesario
        ]);
        Evaluacion::create([
            'codigo_materia' => 103,
            'codigo_seccion' => 4,
            'titulo' => 'Proyecto Final',
            'porcentaje' => 70,
            'metodologia' => 'Proyecto práctico',
            'fecha' => '2024-03-15',
            'nota' => null, // Asegúrate de que este campo sea nullable si es necesario
        ]);
        Evaluacion::create([
            'codigo_materia' => 104,
            'codigo_seccion' => 5,
            'titulo' => 'Examen Parcial 1',
            'porcentaje' => 30,
            'metodologia' => 'Examen escrito',
            'fecha' => '2024-02-15',
            'nota' => null, // Asegúrate de que este campo sea nullable si es necesario
        ]);
        Evaluacion::create([
            'codigo_materia' => 104,
            'codigo_seccion' => 5,
            'titulo' => 'Proyecto Final',
            'porcentaje' => 70,
            'metodologia' => 'Proyecto práctico',
            'fecha' => '2024-03-15',
            'nota' => null, // Asegúrate de que este campo sea nullable si es necesario
        ]);
        Evaluacion::create([
            'codigo_materia' => 105,
            'codigo_seccion' => 6,
            'titulo' => 'Examen Parcial 1',
            'porcentaje' => 30,
            'metodologia' => 'Examen escrito',
            'fecha' => '2024-02-15',
            'nota' => null, // Asegúrate de que este campo sea nullable si es necesario
        ]);
        Evaluacion::create([
            'codigo_materia' => 105,
            'codigo_seccion' => 6,
            'titulo' => 'Proyecto Final',
            'porcentaje' => 70,
            'metodologia' => 'Proyecto práctico',
            'fecha' => '2024-03-15',
            'nota' => null, // Asegúrate de que este campo sea nullable si es necesario
        ]);

        Asunto::create([
            'nombre' => 'Inscripción Curso Verano',
            'descripcion' => 'Inscripción para el curso de verano de programación avanzada.',
        ]);
        Asunto::create([
            'nombre' => 'Pago de Matrícula',
            'descripcion' => 'Pago de matrícula para el semestre 2024-1.',
        ]);
        Asunto::create([
            'nombre' => 'Solicitud de Beca',
            'descripcion' => 'Solicitud de beca para el programa de posgrado en ingeniería de software.',
        ]);
        Asunto::create([
            'nombre' => 'Consulta Académica',
            'descripcion' => 'Consulta sobre el plan de estudios y materias del programa de ingeniería de software.',
        ]);
        Asunto::create([
            'nombre' => 'Reclamo de Calificación',
            'descripcion' => 'Reclamo sobre la calificación del examen final de la materia de programación avanzada.',
        ]);
        Asunto::create([
            'nombre' => 'Solicitud de Certificado',
            'descripcion' => 'Solicitud de certificado de culminación de estudios en ingeniería de software.',
        ]);
        Asunto::create([
            'nombre' => 'Cambio de Carrera',
            'descripcion' => 'Solicitud de cambio de carrera de ingeniería de software a administración de empresas.',
        ]);
        Asunto::create([
            'nombre' => 'Inscripción a Exámenes',
            'descripcion' => 'Inscripción para los exámenes finales del semestre 2024-1.',
        ]);
        Asunto::create([
            'nombre' => 'Solicitud de Tutoría',
            'descripcion' => 'Solicitud de tutoría académica para mejorar el rendimiento en las materias.',
        ]);
    }
}

