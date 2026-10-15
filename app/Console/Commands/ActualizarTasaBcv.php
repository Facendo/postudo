<?php

namespace App\Console\Commands;

use App\Services\TasaCambioService;
use Illuminate\Console\Command;

class ActualizarTasaBcv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasa:actualizar-bcv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza la tasa de cambio con la tasa oficial publicada por el BCV (vía bcv.today)';

    /**
     * Execute the console command.
     */
    public function handle(TasaCambioService $tasa): int
    {
        try {
            $data = $tasa->actualizarDesdeBcv();
        } catch (\Throwable $e) {
            $this->error('No se pudo actualizar la tasa: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->info(sprintf(
            'Tasa BCV actualizada: Bs. %s / $ (vigencia %s, fuente %s)',
            number_format($data['valor'], 2, ',', '.'),
            $data['fecha'],
            $data['fuente']
        ));

        return self::SUCCESS;
    }
}
