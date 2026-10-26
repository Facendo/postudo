<?php

use App\Http\Controllers\NotificacionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Notificaciones in-app (todos los roles autenticados)
|--------------------------------------------------------------------------
|
| Bandeja + marcado de leídas para cualquier usuario; el alcance se filtra
| SIEMPRE por el usuario de la sesión en el controller. El envío de
| comunicados es exclusivo de administrador y coordinador general.
|
| Este archivo no tiene efecto hasta que se requiera desde routes/web.php.
|
*/

Route::middleware('auth')->group(function () {
    Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('notificacion.index');
    Route::get('/notificaciones/contador', [NotificacionController::class, 'contador'])->name('notificacion.contador');
    Route::post('/notificaciones/{id}/leer', [NotificacionController::class, 'leer'])->name('notificacion.leer');
    Route::post('/notificaciones/leer-todas', [NotificacionController::class, 'leerTodas'])->name('notificacion.leer_todas');
});

// Comunicados: emiten administrador y coordinador general.
Route::middleware(['auth', 'role:administrador|coordinador_general'])->group(function () {
    Route::get('/notificaciones/comunicar', [NotificacionController::class, 'comunicar'])->name('notificacion.comunicar');
    Route::post('/notificaciones/comunicar', [NotificacionController::class, 'enviarComunicado'])->name('notificacion.enviar');
});
