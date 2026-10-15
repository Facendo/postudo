<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Sincronización automática de la tasa de cambio con el BCV.
// Cada hora en el minuto 0 (hora de Caracas) se intenta actualizar; el BCV
// publica ~16:30 y la API refleja el cambio en minutos. En local (Windows)
// esto no corre solo: el respaldo es el fallback perezoso del panel y el
// botón "Sincronizar con BCV". En hosting con cron basta una entrada:
//   * * * * * php artisan schedule:run
Schedule::command('tasa:actualizar-bcv')
    ->hourlyAt(0)
    ->timezone('America/Caracas')
    ->withoutOverlapping();
