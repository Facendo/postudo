<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagoController;
use App\Http\Middleware\CheckRole;

Route::get('/estudiante/pago', [PagoController::class, 'index'])->middleware('auth','role:estudiante')->name('pago.index');
Route::get('estudiante/registrar-pago', [PagoController::class, 'create'])->middleware('auth','role:estudiante')->name('pago.create');
Route::post('estudiante/registrar-pago', [PagoController::class, 'store'])->middleware('auth','role:estudiante')->name('pago.store');
