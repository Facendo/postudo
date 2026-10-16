<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Centraliza la lectura y escritura de la tasa de cambio
 * (tasa_cambio.json y tasa_cambio_historial.json en storage/app/private).
 */
class TasaCambioService
{
    /**
     * API comunitaria gratuita (sin clave) que replica las tasas oficiales
     * publicadas por el BCV. Verificada: responde JSON con USD y date.
     */
    public const URL_API_BCV = 'https://bcv.today/api/v1/rate.json';

    /** Horas máximas de antigüedad para considerar la tasa almacenada "fresca". */
    public const HORAS_VIGENCIA_LOCAL = 6;

    /** Minutos de espera antes de reintentar tras un fallo de la API (evita martillearla). */
    public const MINUTOS_REINTENTO = 15;

    /**
     * Lee la tasa actual almacenada.
     */
    public function obtenerTasaActual(): array
    {
        if (Storage::exists('tasa_cambio.json')) {
            return json_decode(Storage::get('tasa_cambio.json'), true) ?? [];
        }

        return [];
    }

    /**
     * Lee el historial de tasas (fecha Y-m-d => valor).
     */
    public function obtenerHistorial(): array
    {
        if (Storage::exists('tasa_cambio_historial.json')) {
            return json_decode(Storage::get('tasa_cambio_historial.json'), true) ?? [];
        }

        return [];
    }

    /**
     * Descarga la tasa vigente del BCV (vía bcv.today) y la guarda en
     * tasa_cambio.json + tasa_cambio_historial.json.
     *
     * Si la API falla o devuelve datos inválidos NO se modifica nada:
     * se conserva la última tasa buena registrada.
     *
     * @return array Datos de la tasa guardada {valor, fecha, hora, fuente, fecha_iso}
     *
     * @throws \Exception Cuando la API falla o responde datos inválidos.
     */
    public function actualizarDesdeBcv(): array
    {
        $respuesta = Http::timeout(5)
            ->withHeaders(['Cache-Control' => 'no-cache'])
            ->get(self::URL_API_BCV);

        if (! $respuesta->ok()) {
            throw new \Exception('La API del BCV respondió con código '.$respuesta->status().'.');
        }

        $valor = $respuesta->json('USD');

        if (! is_numeric($valor) || $valor <= 0) {
            throw new \Exception('La API del BCV no devolvió una tasa USD válida.');
        }

        // Clave del historial = día calendario (date), NO effective_date:
        // así la tasa guardada para un día es la vigente ese día, lo que respeta
        // la captura "tasa del día del depósito" que hace PagoController.
        $fechaIso = $respuesta->json('date') ?: now()->format('Y-m-d');

        return $this->guardar((float) $valor, $fechaIso, 'BCV');
    }

    /**
     * Guarda la tasa capturada a mano (respaldo de emergencia).
     */
    public function guardarManual(float $valor): array
    {
        return $this->guardar($valor, now()->format('Y-m-d'), 'manual');
    }

    /**
     * Fallback perezoso: si la tasa almacenada no es de hoy (o tiene más de
     * HORAS_VIGENCIA_LOCAL horas), intenta sincronizarla con el BCV. Si la
     * sincronización falla, se conserva silenciosamente la última tasa buena.
     */
    public function asegurarTasaAlDia(): void
    {
        try {
            $actual = $this->obtenerTasaActual();
            $hoy = now()->format('Y-m-d');

            // Si acaba de fallar, esperar un poco antes de reintentar.
            if (! empty($actual['ultimo_fallo'])) {
                $minutosDesdeFallo = Carbon::parse($actual['ultimo_fallo'])->diffInMinutes(now());
                if ($minutosDesdeFallo < self::MINUTOS_REINTENTO) {
                    return;
                }
            }

            $desactualizada = ($actual['fecha_iso'] ?? null) !== $hoy
                || empty($actual['valor'])
                || (isset($actual['actualizada_en'])
                    && Carbon::parse($actual['actualizada_en'])->diffInHours(now()) >= self::HORAS_VIGENCIA_LOCAL);

            if ($desactualizada) {
                $this->actualizarDesdeBcv();
            }
        } catch (\Throwable $e) {
            // Sin conexión o API caída: se mantiene la última tasa registrada.
            $this->registrarFallo();
            Log::warning('No fue posible sincronizar la tasa de cambio con el BCV: '.$e->getMessage());
        }
    }

    /**
     * Escribe la tasa actual y su historial en disco.
     *
     * @param  string  $fechaIso  Día calendario (Y-m-d) al que corresponde la tasa.
     * @param  string  $fuente  'BCV' (automática) o 'manual' (captura de emergencia).
     */
    private function guardar(float $valor, string $fechaIso, string $fuente): array
    {
        $data = [
            'valor' => $valor,
            'fecha' => Carbon::createFromFormat('Y-m-d', $fechaIso)->format('d/m/Y'),
            'hora' => now()->format('H:i'),
            'fuente' => $fuente,
            'fecha_iso' => $fechaIso,
            'actualizada_en' => now()->toDateTimeString(),
        ];

        Storage::put('tasa_cambio.json', json_encode($data));

        // Guardar en el historial (clave = día calendario, ordenado)
        $historial = $this->obtenerHistorial();
        $historial[$fechaIso] = $valor;
        ksort($historial);
        Storage::put('tasa_cambio_historial.json', json_encode($historial));

        return $data;
    }

    /**
     * Marca el momento del último fallo para aplicar espera antes de reintentar.
     */
    private function registrarFallo(): void
    {
        $actual = $this->obtenerTasaActual();

        // Solo tiene sentido si ya existe una tasa guardada.
        if (! empty($actual['valor'])) {
            $actual['ultimo_fallo'] = now()->toDateTimeString();
            Storage::put('tasa_cambio.json', json_encode($actual));
        }
    }
}
