<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagoController;
use App\Http\Middleware\CheckRole;

Route::get('/estudiante/pago', [PagoController::class, 'index'])->middleware('auth','role:estudiante')->name('pago.index');
Route::get('estudiante/registrar-pago', [PagoController::class, 'create'])->middleware('auth','role:estudiante')->name('pago.create');
Route::post('estudiante/registrar-pago', [PagoController::class, 'store'])->middleware('auth','role:estudiante')->name('pago.store');
Route::get('estudiante/pago/detalles', [PagoController::class, 'controlpagos'])->middleware('auth','role:administrador')->name('pago.controlpagos');
Route::post('/asuntos/Actualizar/{id}', [PagoController::class, 'ActualizarEstado'])->middleware('auth','role:administrador')->name('pago.actualizar');