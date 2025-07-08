<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\EspecialidadesController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CarreraController;
use App\Http\Middleware\CheckRole;

Route::get(("/administrador"), [AdministradorController::class, 'index'])->middleware('auth','role:administrador')->name(name: 'administrador.index');
Route::get(("/administrador/gestion_estudiante"), [EstudianteController::class, 'list'])->middleware('auth','role:administrador')->name('estudiante.list');
Route::get(("/administrador/registro_estudiante"), [EstudianteController::class, 'create'])->middleware('auth','role:administrador')->name('registro_estudiante.index');
Route::post(("/administrador/registro_estudiante"), [EstudianteController::class, 'store'])->middleware('auth','role:administrador')->name('estudiante.store');
Route::get(("/administrador/areas"), [AdministradorController::class, 'creacionArea'])->middleware('auth','role:administrador')->name('administrador.creacion.index');
Route::post(("/administrador/areas"), [AreaController::class, 'store'])->middleware('auth','role:administrador')->name('administrador.area.store');
Route::post("/administrador/carrera", [CarreraController::class, 'store'])->middleware('auth','role:administrador')->name('administrador.carrera.store');
Route::post("/administrador/especialidad", [EspecialidadesController::class, 'store'])->middleware('auth','role:administrador')->name('administrador.especialidad.store');