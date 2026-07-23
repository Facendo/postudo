<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsuntoController;
use App\Http\Middleware\CheckRole;

Route::middleware(['auth', 'role:administrador'])->group(function () {
    Route::get('/asuntos', [AsuntoController::class, 'index'])->name('asuntos.index');
    Route::get('/asuntos/create', [AsuntoController::class, 'create'])->name('asuntos.create');
    Route::post('/asuntos', [AsuntoController::class, 'store'])->name('asuntos.store');
    Route::get('/asuntos/{id}/edit', [AsuntoController::class, 'edit'])->name('asuntos.edit');
    Route::put('/asuntos/{id}', [AsuntoController::class, 'update'])->name('asuntos.update');
    Route::delete('/asuntos/{id}', [AsuntoController::class, 'destroy'])->name('asuntos.destroy');
    
});