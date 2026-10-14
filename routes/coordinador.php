<?php

use App\Http\Controllers\CoordinadorGeneralController;
use Illuminate\Support\Facades\Route;

// Rutas del panel de coordinador general (requieren autenticación y rol de 'coordinador_general')
Route::middleware(['auth', 'role:coordinador_general'])->group(function () {

    // Panel principal del coordinador general
    Route::get("/coordinador", [CoordinadorGeneralController::class, 'index'])->name('coordinador.index');

});
