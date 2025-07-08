<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstudianteController;
use App\Http\Middleware\CheckRole;

Route::get('/estudiante', [EstudianteController::class, 'index'])->middleware('auth','role:estudiante')->name('estudiante.index');
Route::get('/estudiante/perfil', [EstudianteController::class, 'showPerfil'])->middleware('auth','role:estudiante')->name('estudiante.perfil');
Route::get('/estudiante/registro', [EstudianteController::class, 'create'])->middleware('auth','role:administrador')->name('estudiante.create');
Route::post('/estudiante/registro', [EstudianteController::class, 'store'])->middleware('auth','role:administrador')->name('estudiante.store');