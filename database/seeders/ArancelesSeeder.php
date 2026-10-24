<?php

namespace Database\Seeders;

use App\Models\Asunto;
use Illuminate\Database\Seeder;

/**
 * Carga los 15 aranceles de la normativa vigente (la misma que muestra
 * la «Gaceta de Aranceles» de la portada) como asuntos base:
 * sin cedula_estudiante, activos, visibles para el estudiante en su
 * página de precios (conversión a Bs. según la tasa BCV del día).
 *
 * Antes de insertar borra TODA la tabla `asunto`: los registros que
 * había eran de prueba y ya no se usan (decisión del usuario,
 * 2026-10-24).
 *
 * Ejecutar con: php artisan db:seed --class=ArancelesSeeder
 */
class ArancelesSeeder extends Seeder
{
    /**
     * [nombre del arancel, grupo de la normativa, valor en USD]
     *
     * @var array<int, array{0: string, 1: string, 2: float}>
     */
    private const ARANCELES = [
        ['Preinscripción y revisión de documentos', 'Nuevos ingresos', 10.00],
        ['Arancel básico de inscripción en programas modulares', 'Nuevos ingresos', 15.00],
        ['Matrícula para asignaturas', 'Escolaridad', 30.00],
        ['Aranceles de permanencia', 'Permanencia', 15.00],
        ['Registro y evaluación del proyecto de investigación', 'Trabajo de grado', 15.00],
        ['Inscripción del trabajo de grado', 'Trabajo de grado', 10.00],
        ['Defensa del trabajo de grado', 'Trabajo de grado', 80.00],
        ['Elaboración de expediente para grado', 'Acto de grado', 10.00],
        ['Solicitud de solvencias administrativas', 'Acto de grado', 5.00],
        ['Constancias de estudios', 'Acto de grado', 5.00],
        ['Constancias de notas no certificadas', 'Acto de grado', 5.00],
        // «Materia de Nivelación: entre $ 30,00» en la normativa: se toma el mínimo.
        ['Materia de Nivelación', 'Otros aranceles de Escolaridad', 30.00],
        ['Examen de Suficiencia o Especial', 'Otros aranceles de Escolaridad', 30.00],
        ['Examen de Suficiencia de un idioma extranjero', 'Otros aranceles de Escolaridad', 20.00],
        ['Trámite Curso Dirigido', 'Otros aranceles de Escolaridad', 10.00],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Todo lo anterior era de prueba: se limpia la tabla completa.
        Asunto::query()->delete();

        foreach (self::ARANCELES as [$nombre, $grupo, $valor]) {
            Asunto::create([
                'nombre' => $nombre,
                'descripcion' => $grupo,
                'valor_usd' => $valor,
                'activo' => true,
                'cedula_estudiante' => null,
            ]);
        }
    }
}
