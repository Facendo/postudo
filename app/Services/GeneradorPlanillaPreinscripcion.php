<?php

namespace App\Services;

use App\Models\Preinscripcion;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

/**
 * Genera la planilla de preinscripción en .docx rellena con los datos del
 * aspirante, a partir de la plantilla marcada con ${placeholders} que vive en
 * storage/app/private/preinscripcion/plantilla.docx.
 *
 * La plantilla se creó una sola vez desde el .doc original de la coordinación
 * (script externo); los anexos y la firma quedan para el trámite físico.
 */
class GeneradorPlanillaPreinscripcion
{
    /**
     * Ruta de la plantilla, relativa a storage/app/private.
     */
    private const PLANTILLA = 'preinscripcion/plantilla.docx';

    /**
     * Columnas de mención de la matriz del .doc (casilla mención x sede).
     * El patrón se compara en minúsculas y sin acentos contra el nombre de la
     * mención elegida; si ninguno coincide, se usa el orden de las menciones
     * del postgrado (las tres columnas del .doc siguen ese orden).
     */
    private const COLUMNAS_MENCION = [
        'cas_gg' => ['geren', 'ger. gral'],
        'cas_fin' => ['finanz'],
        'cas_rrhh' => ['recurso', 'rrhh', 'humano'],
    ];

    /**
     * Documentos del checklist con casillas SI/NO, en el orden del .doc.
     * (El «Original y copia del Depósito» no tiene casillas en la planilla.)
     */
    private const DOCUMENTOS = [
        'cedula' => 1,
        'partida' => 2,
        'militar' => 3,
        'titulo' => 4,
        'notas' => 5,
        'sintesis' => 6,
        'constancia' => 7,
    ];

    /**
     * Genera el .docx relleno y devuelve su ruta absoluta. El archivo se crea
     * en storage/app/private/preinscripcion/generadas y el caller es quien lo
     * borra (response()->download(...)->deleteFileAfterSend(true)).
     */
    public function generar(Preinscripcion $preinscripcion): string
    {
        // Escapar &, <, etc. de los valores antes de inyectarlos en el XML.
        Settings::setOutputEscapingEnabled(true);

        $procesador = new TemplateProcessor(storage_path('app/private/'.self::PLANTILLA));
        $procesador->setValues($this->valores($preinscripcion));

        $directorio = storage_path('app/private/preinscripcion/generadas');
        if (! is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }

        $ruta = $directorio.DIRECTORY_SEPARATOR.'planilla_'.$preinscripcion->cedula.'_'.time().'.docx';
        $procesador->saveAs($ruta);

        return $ruta;
    }

    /**
     * Arreglo placeholder => valor para el TemplateProcessor de PhpWord.
     * Las claves son exactamente los ${...} de la plantilla.
     */
    private function valores(Preinscripcion $p): array
    {
        return array_merge(
            $this->valoresEncabezado($p),
            $this->valoresDatosPersonales($p),
            $this->valoresTrabajo($p),
            $this->valoresFinanciamiento($p),
            $this->valoresGrados($p->grados ?? []),
            $this->valoresReferencias($p->referencias ?? []),
            $this->valoresDocumentos($p->documentos ?? []),
            ['lugar_fecha' => $p->sede.', '.date('d/m/Y')],
        );
    }

    /**
     * Voucher, menciones del postgrado, año automático y casillas de la
     * matriz (mención x sede).
     */
    private function valoresEncabezado(Preinscripcion $p): array
    {
        $mencion = $p->mencion;
        $postgrado = $mencion?->postgrado;
        $menciones = $postgrado
            ? $postgrado->menciones->sortBy('id_mencion')->pluck('nombre')->implode(' / ')
            : '';

        $casillas = ['cas_gg' => '', 'cas_fin' => '', 'cas_rrhh' => ''];

        if ($mencion) {
            $columna = $this->columnaDeMencion($mencion->nombre);

            // Sin coincidencia por nombre: se marca por orden de creación.
            if ($columna === null && $postgrado) {
                $orden = $postgrado->menciones->sortBy('id_mencion')->pluck('id_mencion')->values();
                $posicion = $orden->search($mencion->id_mencion);
                if ($posicion !== false && $posicion < count($casillas)) {
                    $columna = array_keys($casillas)[$posicion];
                }
            }

            if ($columna !== null) {
                $casillas[$columna] = 'X';
            }
        }

        return [
            'voucher_nro' => (string) ($p->voucher_nro ?? ''),
            'voucher_fecha' => $this->fechaPlanilla($p->voucher_fecha),
            'voucher_banco' => (string) ($p->voucher_banco ?? ''),
            'menciones' => $menciones,
            'anio' => date('Y'),
            'cas_bcn' => $p->sede === 'Barcelona' ? 'X' : '',
            'cas_can' => $p->sede === 'Cantaura' ? 'X' : '',
        ] + $casillas;
    }

    /**
     * Datos personales y formación de pregrado.
     */
    private function valoresDatosPersonales(Preinscripcion $p): array
    {
        return [
            'apellidos' => (string) $p->apellido,
            'nombres' => (string) $p->nombre,
            'cedula' => (string) $p->cedula,
            'edad' => (string) $p->edad,
            'sexo' => (string) $p->sexo,
            'estado_civil' => (string) $p->estado_civil,
            'nacionalidad' => (string) $p->nacionalidad,
            'lugar_nacimiento' => (string) $p->lugar_nacimiento,
            'etnia' => (string) ($p->etnia ?? ''),
            'direccion_permanente' => (string) $p->direccion_permanente,
            'telefono' => (string) $p->telefono,
            'email' => (string) $p->correo,
            'direccion_otra' => (string) ($p->direccion_otra ?? ''),
            'telefono_otro' => (string) ($p->telefono_otro ?? ''),
            'correo_otro' => (string) ($p->correo_otro ?? ''),
            'titulo_pregrado' => (string) $p->titulo_pregrado,
            'institucion_pregrado' => (string) $p->institucion_pregrado,
            'anio_grado' => (string) $p->anio_grado,
        ];
    }

    /**
     * Situación laboral (casillas «Trabaja») y textos libres.
     */
    private function valoresTrabajo(Preinscripcion $p): array
    {
        $casillas = ['cas_dep' => '', 'cas_cuenta_propia' => '', 'cas_libre_ejercicio' => '', 'cas_no' => ''];
        $equivalencia = [
            'dependiente' => 'cas_dep',
            'cuenta_propia' => 'cas_cuenta_propia',
            'libre_ejercicio' => 'cas_libre_ejercicio',
            'no' => 'cas_no',
        ];
        if (isset($equivalencia[$p->trabaja])) {
            $casillas[$equivalencia[$p->trabaja]] = 'X';
        }

        return $casillas + [
            'ocupacion' => (string) ($p->ocupacion ?? ''),
            'cargo' => (string) ($p->cargo ?? ''),
            'tiempo_cargo' => (string) ($p->tiempo_cargo ?? ''),
            'empresa' => (string) ($p->empresa ?? ''),
            'direccion_empresa' => (string) ($p->direccion_empresa ?? ''),
            'supervisor' => (string) ($p->supervisor ?? ''),
            'telefono_supervisor' => (string) ($p->telefono_supervisor ?? ''),
            'correo_supervisor' => (string) ($p->correo_supervisor ?? ''),
            'historial_trabajos' => (string) ($p->historial_trabajos ?? ''),
            'experiencia_laboral' => (string) ($p->experiencia_laboral ?? ''),
        ];
    }

    /**
     * Fuente de financiamiento (casillas).
     */
    private function valoresFinanciamiento(Preinscripcion $p): array
    {
        $casillas = ['financiamiento_propia' => '', 'financiamiento_publico' => '', 'financiamiento_otra' => ''];
        $equivalencia = [
            'propia' => 'financiamiento_propia',
            'organismo_publico' => 'financiamiento_publico',
            'otra' => 'financiamiento_otra',
        ];
        if (isset($equivalencia[$p->fuente_financiamiento])) {
            $casillas[$equivalencia[$p->fuente_financiamiento]] = 'X';
        }

        return $casillas;
    }

    /**
     * Tabla de grados (universitario, postgrado y otros).
     */
    private function valoresGrados(array $grados): array
    {
        $valores = [];
        foreach (['universitario', 'postgrado', 'otros'] as $tipo) {
            $fila = $grados[$tipo] ?? [];
            foreach (['institucion', 'titulo', 'anos', 'pais', 'fecha'] as $campo) {
                $valores['grado_'.$tipo.'_'.$campo] = (string) ($fila[$campo] ?? '');
            }
        }

        return $valores;
    }

    /**
     * Las dos referencias personales.
     */
    private function valoresReferencias(array $referencias): array
    {
        $campos = [
            'nombre', 'edad', 'direccion', 'telefono', 'email', 'ocupacion',
            'cargo', 'empresa', 'telefono_trabajo', 'email_trabajo', 'direccion_trabajo',
        ];

        $valores = [];
        foreach ([1, 2] as $indice) {
            $referencia = $referencias[$indice - 1] ?? [];
            foreach ($campos as $campo) {
                $valores['ref'.$indice.'_'.$campo] = (string) ($referencia[$campo] ?? '');
            }
        }

        return $valores;
    }

    /**
     * Checklist de documentación: marca « X» en la casilla SI o NO elegida.
     */
    private function valoresDocumentos(array $documentos): array
    {
        $valores = [];
        foreach (self::DOCUMENTOS as $clave => $numero) {
            $presento = ($documentos[$clave] ?? 'no') === 'si';
            $valores['doc'.$numero.'_si'] = $presento ? ' X' : '';
            $valores['doc'.$numero.'_no'] = $presento ? '' : ' X';
        }

        return $valores;
    }

    /**
     * Columna de la matriz (casillas de mención) según el nombre; null si no
     * hay coincidencia.
     */
    private function columnaDeMencion(string $nombre): ?string
    {
        $nombre = $this->sinAcentos(mb_strtolower($nombre));

        foreach (self::COLUMNAS_MENCION as $columna => $patrones) {
            foreach ($patrones as $patron) {
                if (str_contains($nombre, $patron)) {
                    return $columna;
                }
            }
        }

        return null;
    }

    /**
     * Fecha para la planilla (d/m/Y); devuelve la cadena original si no se
     * puede interpretar.
     */
    private function fechaPlanilla(?string $fecha): string
    {
        if (! $fecha) {
            return '';
        }

        $marca = strtotime($fecha);

        return $marca ? date('d/m/Y', $marca) : $fecha;
    }

    /**
     * Minúsculas sin acentos para comparar nombres de mención.
     */
    private function sinAcentos(string $texto): string
    {
        return strtr($texto, [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ñ' => 'n',
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U', 'Ü' => 'U', 'Ñ' => 'N',
        ]);
    }
}
