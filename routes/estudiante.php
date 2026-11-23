<?php

use App\Http\Controllers\EstudianteController;
use Illuminate\Support\Facades\Route;

Route::get('/estudiante', [EstudianteController::class, 'index'])->middleware('auth', 'role:estudiante')->name('estudiante.index');
Route::get('/estudiante/perfil', [EstudianteController::class, 'showPerfil'])->middleware('auth', 'role:estudiante')->name('estudiante.perfil');
Route::get('/estudiante/perfil/editar', [EstudianteController::class, 'editPerfil'])->middleware('auth', 'role:estudiante')->name('estudiante.perfil.edit');
Route::put('/estudiante/perfil/editar', [EstudianteController::class, 'update_Perfil_estudiante'])->middleware('auth', 'role:estudiante')->name('estudiante.perfil_update');
Route::get('/estudiante/inscripcion', [EstudianteController::class, 'inscripcion'])->middleware('auth', 'role:estudiante')->name('estudiante.inscripcion');
// El PUT estudiante.inscribir se eliminó el 2026-11-23: quien inscribe es el
// coordinador de postgrado (apartado de Inscripciones); el estudiante solo consulta.
