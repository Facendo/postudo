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
Route::get('administrador/pagos-actualizados', [PagoController::class, 'pagosActualizados'])->middleware('auth','role:administrador')->name('pago.actualizados');
Route::get('administrador/pagos-pendientes', [PagoController::class, 'pagosPendientes'])->middleware('auth','role:administrador')->name('pago.pendientes');
Route::get('administrador/verificar-pagos', [PagoController::class, 'verificarPagos'])->middleware('auth','role:administrador')->name('pago.verificar');
Route::post('administrador/confirmar-verificados', [PagoController::class, 'confirmarVerificados'])->middleware('auth','role:administrador')->name('pago.confirmarVerificados');
Route::post('administrador/configurar-sheet', [PagoController::class, 'configurarSheet'])->middleware('auth','role:administrador')->name('pago.configurarSheet');