<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Area;
use App\Models\Carrera;
use App\Models\Cohorte;
use App\Models\Especialidades;
use App\Models\Postgrado;

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
    }
}

