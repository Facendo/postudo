<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\ProfesorController;


Route::view(("/profesor"), 'profesor.index')->name('profesor.index');
Route::get('/profesor/perfil', [ProfesorController::class, 'perfil'])->name('profesor.perfil');
Route::get('/profesor/listadoestudiantes', [ProfesorController::class, 'listadoestudiantes'])->name('profesor.listadoestudiantes');
Route::get('/profesor/gestionevaluacion', [EvaluacionController::class, 'index'])->name('profesor.gestionevaluacion.index');
Route::get('/profesor/gestionevaluacion/create', [EvaluacionController::class, 'create'])->name('profesor.gestionevaluacion.create');
Route::post('/profesor/gestionevaluacion', [EvaluacionController::class, 'store'])->name('profesor.gestionevaluacion.store');
Route::get('/profesor/gestionevaluacion/{id_evaluacion}/edit', [EvaluacionController::class, 'edit'])->name('profesor.gestionevaluacion.edit');
Route::put('/profesor/gestionevaluacion/{id_evaluacion}', [EvaluacionController::class, 'update'])->name('profesor.gestionevaluacion.update');
Route::delete('/profesor/gestionevaluacion/{id_evaluacion}', [EvaluacionController::class, 'destroy'])->name('profesor.gestionevaluacion.destroy');

