<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\EspecialidadesController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\ProfesorController;
use App\Http\Middleware\CheckRole;

Route::get(("/administrador"), [AdministradorController::class, 'index'])->middleware('auth','role:administrador')->name(name: 'administrador.index');
Route::get(("/administrador/gestion_estudiante"), [EstudianteController::class, 'list'])->middleware('auth','role:administrador')->name('estudiante.list');
Route::get(("/administrador/registro_estudiante"), [EstudianteController::class, 'create'])->middleware('auth','role:administrador')->name('registro_estudiante.index');
Route::post(("/administrador/registro_estudiante"), [EstudianteController::class, 'store'])->middleware('auth','role:administrador')->name('estudiante.store');
Route::get(("/administrador/areas"), [AdministradorController::class, 'creacionArea'])->middleware('auth','role:administrador')->name('administrador.creacion.index');
Route::post(("/administrador/areas"), [AreaController::class, 'store'])->middleware('auth','role:administrador')->name('administrador.area.store');
Route::post("/administrador/carrera", [CarreraController::class, 'store'])->middleware('auth','role:administrador')->name('administrador.carrera.store');
Route::post("/administrador/especialidad", [EspecialidadesController::class, 'store'])->middleware('auth','role:administrador')->name('administrador.especialidad.store');
Route::get("/administrador/gestion_profesor", [ProfesorController::class, 'index'])->middleware('auth','role:administrador')->name('administrador.gestion_profesor');
Route::get("/administrador/gestion_profesor/create", [ProfesorController::class, 'create'])->middleware('auth','role:administrador')->name('administrador.gestion_profesor.create');
Route::post("/administrador/gestion_profesor", [ProfesorController::class, 'store'])->middleware('auth','role:administrador')->name('administrador.gestion_profesor.store');
Route::get("/administrador/gestion_profesor/{profesor}/edit", [ProfesorController::class, 'edit'])->middleware('auth','role:administrador')->name('administrador.gestion_profesor.edit');
Route::put("/administrador/gestion_profesor/{profesor}", [ProfesorController::class, 'update'])->middleware('auth','role:administrador')->name('administrador.gestion_profesor.update');
Route::delete("/administrador/gestion_profesor/{profesor}", [ProfesorController::class, 'destroy'])->middleware('auth','role:administrador')->name('administrador.gestion_profesor.destroy');