<?php

use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\CoordinadorGeneralController;
use App\Http\Controllers\EspecialidadesController;
use App\Http\Controllers\EstudianteController;
use Illuminate\Support\Facades\Route;

// Rutas del panel de coordinador general (requieren autenticación y rol de 'coordinador_general')
Route::middleware(['auth', 'role:coordinador_general'])->group(function () {

    // Panel principal del coordinador general
    Route::get("/coordinador", [CoordinadorGeneralController::class, 'index'])->name('coordinador.index');

    // Gestión de Estudiantes (trasladada desde el panel del administrador, 2026-10-14)
    Route::get('/coordinador/gestionestudiantes', [EstudianteController::class, 'list'])->name('administrador.gestionestudiantes'); // Lista de estudiantes
    Route::get('/coordinador/gestion_estudiante', [EstudianteController::class, 'list'])->name('estudiante.list'); // Otra ruta para listar estudiantes
    Route::get('/coordinador/registro_estudiante', [EstudianteController::class, 'create'])->name('registro_estudiante.index'); // Formulario de registro de estudiante
    Route::post('/coordinador/registro_estudiante', [EstudianteController::class, 'store'])->name('estudiante.store'); // Guardar nuevo estudiante
    Route::get('/coordinador/gestion_estudiante/{estudiante}/edit', [EstudianteController::class, 'edit'])->name('administrador.gestion_estudiante.edit'); // Formulario de edición de estudiante
    Route::put('/coordinador/gestion_estudiante/{estudiante}', [EstudianteController::class, 'update'])->name('administrador.gestion_estudiante.update'); // Actualizar estudiante
    Route::delete('/coordinador/gestion_estudiante/{estudiante}', [EstudianteController::class, 'destroy'])->name('administrador.gestion_estudiante.destroy'); // Eliminar estudiante
    Route::get('/coordinador/prueba_especialidades', [EstudianteController::class, 'listByEspecialidad'])->name('administrador.prueba_especialidades'); // Lista de estudiantes por especialidad

    // Gestión de Especialidades (trasladada desde el panel del administrador, 2026-10-14)
    Route::get('/coordinador/especialidades', [EspecialidadesController::class, 'index'])->name('administrador.especialidades.index');
    Route::post('/coordinador/especialidad', [EspecialidadesController::class, 'store'])->name('administrador.especialidad.store'); // Guardar nueva especialidad
    Route::get('/coordinador/especialidad/{especialidad}/edit', [EspecialidadesController::class, 'edit'])->name('administrador.especialidad.edit'); // Formulario de edición de especialidad
    Route::put('/coordinador/especialidad/{especialidad}', [EspecialidadesController::class, 'update'])->name('administrador.especialidad.update'); // Actualizar especialidad
    Route::delete('/coordinador/especialidad/{especialidad}', [EspecialidadesController::class, 'destroy'])->name('administrador.especialidad.destroy'); // Eliminar especialidad

    // Creación de Áreas, Carreras y Especialidades (página-hub, trasladada desde el administrador, 2026-10-14)
    Route::get('/coordinador/areas', [AdministradorController::class, 'creacionArea'])->name('administrador.creacion.index'); // Vista para creación de áreas
    Route::post('/coordinador/areas', [AreaController::class, 'store'])->name('administrador.area.store'); // Guardar nueva área
    Route::get('/coordinador/areas/{area}/edit', [AreaController::class, 'edit'])->name('administrador.area.edit'); // Formulario de edición de área
    Route::put('/coordinador/areas/{area}', [AreaController::class, 'update'])->name('administrador.area.update'); // Actualizar área
    Route::delete('/coordinador/areas/{area}', [AreaController::class, 'destroy'])->name('administrador.area.destroy'); // Eliminar área
    Route::post('/coordinador/carrera', [CarreraController::class, 'store'])->name('administrador.carrera.store'); // Guardar nueva carrera
    Route::get('/coordinador/carrera/{carrera}/edit', [CarreraController::class, 'edit'])->name('administrador.carrera.edit'); // Formulario de edición de carrera
    Route::put('/coordinador/carrera/{carrera}', [CarreraController::class, 'update'])->name('administrador.carrera.update'); // Actualizar carrera
    Route::delete('/coordinador/carrera/{carrera}', [CarreraController::class, 'destroy'])->name('administrador.carrera.destroy'); // Eliminar carrera
});
