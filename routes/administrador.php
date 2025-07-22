<?php

use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\EspecialidadesController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\MateriasController;
use App\Http\Controllers\PostgradoController;
use App\Http\Controllers\ProfesorController;
use Illuminate\Support\Facades\Route;

// Rutas del panel de administrador (requieren autenticación y rol de 'administrador')
Route::middleware(['auth', 'role:administrador'])->group(function () {

    // Panel principal del administrador
    Route::get("/administrador", [AdministradorController::class, 'index'])->name('administrador.index');

    // Gestión de Estudiantes
    Route::get('/administrador/gestionestudiantes', [EstudianteController::class, 'list'])->name('administrador.gestionestudiantes'); // Lista de estudiantes
    Route::get("/administrador/gestion_estudiante", [EstudianteController::class, 'list'])->name('estudiante.list'); // Otra ruta para listar estudiantes
    Route::get("/administrador/registro_estudiante", [EstudianteController::class, 'create'])->name('registro_estudiante.index'); // Formulario de registro de estudiante
    Route::post("/administrador/registro_estudiante", [EstudianteController::class, 'store'])->name('estudiante.store'); // Guardar nuevo estudiante
    Route::get("/administrador/gestion_estudiante/{estudiante}/edit", [EstudianteController::class, 'edit'])->name('administrador.gestion_estudiante.edit'); // Formulario de edición de estudiante
    Route::put("/administrador/gestion_estudiante/{estudiante}", [EstudianteController::class, 'update'])->name('administrador.gestion_estudiante.update'); // Actualizar estudiante
    Route::delete("/administrador/gestion_estudiante/{estudiante}", [EstudianteController::class, 'destroy'])->name('administrador.gestion_estudiante.destroy'); // Eliminar estudiante

    // Gestión de Áreas
    Route::get("/administrador/areas", [AdministradorController::class, 'creacionArea'])->name('administrador.creacion.index'); // Vista para creación de áreas
    Route::post("/administrador/areas", [AreaController::class, 'store'])->name('administrador.area.store'); // Guardar nueva área
    Route::get("/administrador/areas/{area}/edit", [AreaController::class, 'edit'])->name('administrador.area.edit'); // Formulario de edición de área
    Route::put("/administrador/areas/{area}", [AreaController::class, 'update'])->name('administrador.area.update'); // Actualizar área
    Route::delete("/administrador/areas/{area}", [AreaController::class, 'destroy'])->name('administrador.area.destroy'); // Eliminar área

    // Gestión de Carreras
    Route::post("/administrador/carrera", [CarreraController::class, 'store'])->name('administrador.carrera.store'); // Guardar nueva carrera
    Route::get("administrador/carrera/{carrera}/edit", [CarreraController::class, 'edit'])->name('administrador.carrera.edit'); // Formulario de edición de carrera
    Route::put("administrador/carrera/{carrera}", [CarreraController::class, 'update'])->name('administrador.carrera.update'); // Actualizar carrera
    Route::delete("administrador/carrera/{carrera}", [CarreraController::class, 'destroy'])->name('administrador.carrera.destroy'); // Eliminar carrera

    // Gestión de Especialidades
    Route::post("/administrador/especialidad", [EspecialidadesController::class, 'store'])->name('administrador.especialidad.store'); // Guardar nueva especialidad
    Route::get("administrador/especialidad/{especialidad}/edit", [EspecialidadesController::class, 'edit'])->name('administrador.especialidad.edit'); // Formulario de edición de especialidad
    Route::put("administrador/especialidad/{especialidad}", [EspecialidadesController::class, 'update'])->name('administrador.especialidad.update'); // Actualizar especialidad
    Route::delete("administrador/especialidad/{especialidad}", [EspecialidadesController::class, 'destroy'])->name('administrador.especialidad.destroy'); // Eliminar especialidad

    // Gestión de Profesores
    Route::get("/administrador/gestion_profesor", [ProfesorController::class, 'index'])->name('administrador.gestion_profesor'); // Lista de profesores
    Route::get("/administrador/gestion_profesor/create", [ProfesorController::class, 'create'])->name('administrador.gestion_profesor.create'); // Formulario de registro de profesor
    Route::post("/administrador/gestion_profesor", [ProfesorController::class, 'store'])->name('administrador.gestion_profesor.store'); // Guardar nuevo profesor
    Route::get("/administrador/gestion_profesor/{profesor}/edit", [ProfesorController::class, 'edit'])->name('administrador.gestion_profesor.edit'); // Formulario de edición de profesor
    Route::put("/administrador/gestion_profesor/{profesor}", [ProfesorController::class, 'update'])->name('administrador.gestion_profesor.update'); // Actualizar profesor
    Route::delete("/administrador/gestion_profesor/{profesor}", [ProfesorController::class, 'destroy'])->name('administrador.gestion_profesor.destroy'); // Eliminar profesor

    // Gestión de Postgrados
    Route::get("/administrador/gestion_postgrado", [PostgradoController::class, 'index'])->name('administrador.gestion_postgrado'); // Lista de postgrados
    Route::get("/administrador/gestion_postgrado/create", [PostgradoController::class, 'create'])->name('administrador.gestion_postgrado.create'); // Formulario de registro de postgrado
    Route::post("/administrador/gestion_postgrado", [PostgradoController::class, 'store'])->name('administrador.gestion_postgrado.store'); // Guardar nuevo postgrado
    Route::get("/administrador/gestion_postgrado/{postgrado}/edit", [PostgradoController::class, 'edit'])->name('administrador.gestion_postgrado.edit'); // Formulario de edición de postgrado
    Route::put("/administrador/gestion_postgrado/{postgrado}", [PostgradoController::class, 'update'])->name('administrador.gestion_postgrado.update'); // Actualizar postgrado
    Route::delete("/administrador/gestion_postgrado/{postgrado}", [PostgradoController::class, 'destroy'])->name('administrador.gestion_postgrado.destroy'); // Eliminar postgrado

    // Gestión de Materias
    Route::get("/administrador/gestionmaterias", [MateriasController::class, 'index'])->name('administrador.gestionmaterias'); // Lista de materias
    Route::get("/administrador/gestionmaterias/create", [MateriasController::class, 'create'])->name('administrador.gestionmaterias.create'); // Formulario de registro de materia
    Route::post("/administrador/gestionmaterias", [MateriasController::class, 'store'])->name('administrador.gestionmaterias.store'); // Guardar nueva materia
    Route::get("/administrador/gestionmaterias/{materia}/edit", [MateriasController::class, 'edit'])->name('administrador.gestionmaterias.edit'); // Formulario de edición de materia
    Route::put("/administrador/gestionmaterias/{materia}", [MateriasController::class, 'update'])->name('administrador.gestionmaterias.update'); // Actualizar materia
    Route::delete("/administrador/gestionmaterias/{materia}", [MateriasController::class, 'destroy'])->name('administrador.gestionmaterias.destroy'); // Eliminar materia
});
