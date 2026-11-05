<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

/**
 * Normativa de aranceles de la portada (modal «Aranceles»).
 *
 * El administrador pega el comunicado CRUDO en /administrador/aranceles;
 * se guarda en storage/app/private/aranceles.json y la portada lo parsea
 * al formato visual del modal (introducción, aviso BCV y grupos de
 * concepto/precio), sin interpretar HTML del usuario (todo se escapa al
 * renderizar con {{ }}).
 *
 * Reglas del parseo (las mismas que se muestran en el formulario):
 *  - línea que empieza con ✅  -> arancel (concepto + precio al final);
 *  - línea en MAYÚSCULAS      -> título de grupo (p. ej. ESCOLARIDAD:);
 *  - línea corta sin punto, después de un grupo -> título de grupo;
 *  - línea con «tasa de cambio» o «BCV» -> aviso destacado;
 *  - el resto -> introducción (antes del primer grupo) o notas del grupo.
 *    Las líneas en blanco separan párrafos.
 */
class ArancelesService
{
    /** Archivo JSON (disco local: storage/app/private/aranceles.json). */
    public const RUTA = 'aranceles.json';

    /**
     * Texto por defecto: comunicado original de la normativa vigente
     * desde el 11 de abril de 2025. Se usa mientras el administrador
     * no guarde ninguna actualización (y si el archivo no existe).
     */
    public const TEXTO_DEFECTO = <<<'TEXTO'
Buen día Maestrantes.

En esta ocasión nos comunicamos con ustedes con la finalidad de notificarles que a partir del día 11 de abril de 2025, los montos de los Aranceles a pagar en el Programa de Postgrados de Ciencias Administrativas de la Universidad de Oriente Núcleo Anzoátegui serán los siguientes:

Los aranceles serán pagados a la tasa de cambio del Banco Central de Venezuela (BCV) del día en que se haga la transacción.

NUEVOS INGRESOS ✅
✅Preinscripción y revisión de documentos $ 10,00
✅Arancel básico de inscripción en programas modulares $ 15,00

ESCOLARIDAD:
✅ Matrícula para asignaturas $ 30,00

PERMANENCIA:
✅ Aranceles de permanencia: $ 15,00

TRABAJO DE GRADO:
✅ Registro y evaluación del proyecto de investigación $ 15,00
✅ Inscripción del trabajo de grado $ 10,00
✅ Defensa del trabajo de grado $ 80,00

ACTO DE GRADO:
✅ Elaboración de expediente para grado $ 10,00
✅ Solicitud de solvencias administrativas $ 5,00
✅ Constancias de estudios $ 5,00
✅ Constancias de notas no certificadas $ 5,00

Otros aranceles de Escolaridad
✅ Materia de Nivelación: entre $ 30,00
✅ Examen de Suficiencia o Especial $ 30,00
✅ Examen de Suficiencia de un idioma extranjero $ 20,00
✅ Trámite Curso Dirigido $ 10,00
TEXTO;

    /**
     * Texto crudo vigente: el del archivo si existe y no está vacío;
     * si no, el texto por defecto (la portada nunca queda vacía).
     */
    public function obtenerTexto(): string
    {
        $texto = (string) ($this->leer()['texto'] ?? '');

        return trim($texto) !== '' ? $texto : self::TEXTO_DEFECTO;
    }

    /**
     * Datos de la última actualización para mostrar en el formulario.
     *
     * @return array{actualizado_en: string|null, actualizado_por: string|null}
     */
    public function metadatos(): array
    {
        $datos = $this->leer();

        return [
            'actualizado_en' => $datos['actualizado_en'] ?? null,
            'actualizado_por' => $datos['actualizado_por'] ?? null,
        ];
    }

    /**
     * Guarda el texto crudo del administrador (con quién y cuándo).
     */
    public function guardar(string $texto, ?User $usuario = null): void
    {
        $responsable = 'desconocido';
        if ($usuario) {
            $identificador = $usuario->cedula ?: $usuario->email;
            $responsable = trim($usuario->name.' ('.$identificador.')');
        }

        Storage::put(self::RUTA, json_encode([
            'texto' => $texto,
            'actualizado_en' => now()->format('d/m/Y H:i'),
            'actualizado_por' => $responsable,
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    /**
     * Convierte el texto crudo en la estructura que renderiza el modal.
     *
     * @return array{intro: array<int, string>, aviso: string|null, grupos: array<int, array{titulo: string, items: array<int, array{concepto: string, precio: string}>, notas: array<int, string>}>}
     */
    public function parsear(string $texto): array
    {
        $intro = [];
        $aviso = null;
        $grupos = [];
        $pendiente = '';
        $hayGrupo = false;

        // Vacía el párrafo en construcción a donde corresponda.
        $volcar = function () use (&$pendiente, &$intro, &$grupos, &$aviso, &$hayGrupo) {
            $parrafo = trim($pendiente);
            $pendiente = '';

            if ($parrafo === '') {
                return;
            }

            if ($aviso === null && ! $hayGrupo && $this->esAviso($parrafo)) {
                $aviso = $parrafo;
            } elseif ($hayGrupo) {
                $grupos[count($grupos) - 1]['notas'][] = $parrafo;
            } else {
                $intro[] = $parrafo;
            }
        };

        $lineas = preg_split('/\r\n|\r|\n/', $texto) ?: [];

        foreach ($lineas as $linea) {
            $linea = trim($linea);

            // Línea en blanco: fin de párrafo.
            if ($linea === '') {
                $volcar();

                continue;
            }

            // Arancel: la línea empieza con ✅ (o variantes ✔ ☑ ✓).
            if (preg_match('/^[✅✔☑✓]/u', $linea)) {
                $volcar();

                if (! $hayGrupo) {
                    // Aranceles sin grupo previo: grupo de título vacío.
                    $grupos[] = ['titulo' => '', 'items' => [], 'notas' => []];
                    $hayGrupo = true;
                }

                $grupos[count($grupos) - 1]['items'][] = $this->parsearArancel($linea);

                continue;
            }

            // Título de grupo en MAYÚSCULAS («ESCOLARIDAD:», «NUEVOS INGRESOS ✅»…).
            $titulo = $this->tituloEnMayusculas($linea);
            if ($titulo !== null) {
                $volcar();
                $grupos[] = ['titulo' => $titulo, 'items' => [], 'notas' => []];
                $hayGrupo = true;

                continue;
            }

            // Título corto después de los grupos («Otros aranceles de Escolaridad»).
            if ($hayGrupo && $this->esTituloCorto($linea)) {
                $volcar();
                $grupos[] = ['titulo' => $linea, 'items' => [], 'notas' => []];

                continue;
            }

            // resto: acumula en el párrafo actual (se une con espacio).
            $pendiente .= ($pendiente === '' ? '' : ' ').$linea;
        }

        $volcar();

        return ['intro' => $intro, 'aviso' => $aviso, 'grupos' => $grupos];
    }

    /**
     * Icono de Font Awesome para el título de un grupo (según palabras
     * clave); genérico si el título no se reconoce.
     */
    public function iconoDeGrupo(string $titulo): string
    {
        $t = mb_strtolower($titulo, 'UTF-8');

        return match (true) {
            str_contains($t, 'ingreso') || str_contains($t, 'nuevo') => 'fa-user-plus',
            str_contains($t, 'trabajo') => 'fa-file-pen',
            str_contains($t, 'acto') => 'fa-graduation-cap',
            str_contains($t, 'permanencia') => 'fa-hourglass-half',
            str_contains($t, 'otros') => 'fa-list-check',
            str_contains($t, 'escolaridad') => 'fa-book-open-reader',
            default => 'fa-folder-open',
        };
    }

    /**
     * Divide una línea ✅ en concepto y precio (lo que esté al final
     * con forma de monto, p. ej. «$ 10,00», «entre $ 30,00»).
     *
     * @return array{concepto: string, precio: string}
     */
    private function parsearArancel(string $linea): array
    {
        $linea = trim(preg_replace('/^[✅✔☑✓]\s*/u', '', $linea));

        $patron = '/^(?<concepto>.*?)(?:\s+(?<prefijo>desde|entre)\s+)?(?<monto>\$\s*[\d.,]+)$/iu';
        if (preg_match($patron, $linea, $m)) {
            $concepto = trim($m['concepto'], " \t:：");
            $prefijo = trim($m['prefijo'] ?? '');
            $monto = preg_replace('/\s+/', ' ', $m['monto']);

            return [
                'concepto' => $concepto,
                'precio' => trim($prefijo.' '.$monto),
            ];
        }

        // Sin precio reconocible: se muestra solo el concepto.
        return ['concepto' => $linea, 'precio' => ''];
    }

    /**
     * Título si la línea está en MAYÚSCULAS (se le quitan «:», «…» y
     * marcas ✅ finales). Devuelve null si no lo es.
     */
    private function tituloEnMayusculas(string $linea): ?string
    {
        $sinMarca = preg_replace('/[\s:\.…✅✔☑✓]+$/u', '', $linea);

        if ($sinMarca === null || $sinMarca === '') {
            return null;
        }

        // Debe contener letras (para no confundir «$ 10,00») y estar en mayúsculas.
        if (! preg_match('/\p{L}/u', $sinMarca)) {
            return null;
        }

        return mb_strtoupper($sinMarca, 'UTF-8') === $sinMarca ? $sinMarca : null;
    }

    /**
     * Línea corta sin punto final: se asume título de grupo cuando ya
     * hay grupos (p. ej. «Otros aranceles de Escolaridad»).
     */
    private function esTituloCorto(string $linea): bool
    {
        return mb_strlen($linea, 'UTF-8') <= 60
            && ! preg_match('/[.,;:!?]$/u', $linea)
            && (bool) preg_match('/\p{L}/u', $linea);
    }

    /** ¿Es el aviso de la tasa de cambio del BCV? */
    private function esAviso(string $parrafo): bool
    {
        return (bool) preg_match('/tasa de cambio|\bBCV\b/iu', $parrafo);
    }

    /** Lee el JSON; devuelve [] si no existe o está corrupto. */
    private function leer(): array
    {
        if (! Storage::exists(self::RUTA)) {
            return [];
        }

        $datos = json_decode(Storage::get(self::RUTA), true);

        return is_array($datos) ? $datos : [];
    }
}
