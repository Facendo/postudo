<?php

use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\CohorteController;
use App\Http\Controllers\CoordinadorGeneralController;
use App\Http\Controllers\EgresoController;
use App\Http\Controllers\MateriasController;
use App\Http\Controllers\PostgradoController;
use App\Http\Controllers\ProfesorController;
use App\Http\Controllers\SeccionController;
use Illuminate\Support\Facades\Route;

// Rutas del panel de administrador (requieren autenticación y rol de 'administrador')
Route::middleware(['auth', 'role:administrador'])->group(function () {

    // Panel principal del administrador
    Route::get('/administrador', [AdministradorController::class, 'index'])->name('administrador.index');
    Route::post('/administrador/tasa-cambio', [AdministradorController::class, 'saveTasaCambio'])->name('administrador.tasa_cambio.save');
    Route::post('/administrador/tasa-cambio/bcv', [AdministradorController::class, 'sincronizarTasaBcv'])->name('administrador.tasa_cambio.bcv');

    // Gestión financiera: ingresos (pagos verificados) y egresos
    Route::get('/administrador/finanzas', [EgresoController::class, 'index'])->name('administrador.finanzas'); // Reporte de ingresos y egresos
    Route::get('/administrador/finanzas/egreso/create', [EgresoController::class, 'create'])->name('administrador.egreso.create'); // Formulario de registro de egreso
    Route::post('/administrador/finanzas/egreso', [EgresoController::class, 'store'])->name('administrador.egreso.store'); // Guardar nuevo egreso

    // Gestión de Coordinador General
    Route::get('/administrador/registro_coordinador', [CoordinadorGeneralController::class, 'create'])->name('administrador.coordinador.create');
    Route::post('/administrador/registro_coordinador', [CoordinadorGeneralController::class, 'store'])->name('administrador.coordinador.store');

    // NOTA (2026-10-14): la gestión de estudiantes, especialidades y la creación de
    // áreas/carreras/especialidades se trasladó al panel del coordinador general
    // (ver routes/coordinador.php). El panel del administrador se enfoca en pagos,
    // asuntos y finanzas.

    // Gestión de Profesores
    Route::get('/administrador/gestion_profesor', [ProfesorController::class, 'index'])->name('administrador.gestion_profesor'); // Lista de profesores
    Route::get('/administrador/gestion_profesor/create', [ProfesorController::class, 'create'])->name('administrador.gestion_profesor.create'); // Formulario de registro de profesor
    Route::post('/administrador/gestion_profesor', [ProfesorController::class, 'store'])->name('administrador.gestion_profesor.store'); // Guardar nuevo profesor
    Route::get('/administrador/gestion_profesor/{profesor}/edit', [ProfesorController::class, 'edit'])->name('administrador.gestion_profesor.edit'); // Formulario de edición de profesor
    Route::put('/administrador/gestion_profesor/{profesor}', [ProfesorController::class, 'update'])->name('administrador.gestion_profesor.update'); // Actualizar profesor
    Route::delete('/administrador/gestion_profesor/{profesor}', [ProfesorController::class, 'destroy'])->name('administrador.gestion_profesor.destroy'); // Eliminar profesor

    // Gestión de Postgrados
    Route::get('/administrador/gestion_postgrado', [PostgradoController::class, 'index'])->name('administrador.gestion_postgrado'); // Lista de postgrados
    Route::get('/administrador/gestion_postgrado/create', [PostgradoController::class, 'create'])->name('administrador.gestion_postgrado.create'); // Formulario de registro de postgrado
    Route::post('/administrador/gestion_postgrado', [PostgradoController::class, 'store'])->name('administrador.gestion_postgrado.store'); // Guardar nuevo postgrado
    Route::get('/administrador/gestion_postgrado/{id}/edit', [PostgradoController::class, 'edit'])->name('administrador.gestion_postgrado.edit'); // Formulario de edición de postgrado
    Route::get('/administrador/gestion_postgrado/{id}/detalles', [PostgradoController::class, 'showdetalles'])->name('administrador.gestion_postgrado.showdetalles'); // Ver detalles de postgrado
    Route::put('/administrador/gestion_postgrado/{id}', [PostgradoController::class, 'update'])->name('administrador.gestion_postgrado.update'); // Actualizar postgrado
    Route::delete('/administrador/gestion_postgrado/{id}', [PostgradoController::class, 'destroy'])->name('administrador.gestion_postgrado.destroy'); // Eliminar postgrado

    // Gestión de Materias
    Route::get('/administrador/gestionmaterias', [MateriasController::class, 'index'])->name('administrador.gestionmaterias'); // Lista de materias
    Route::get('/administrador/gestionmaterias/{id}/create', [MateriasController::class, 'create'])->name('administrador.gestionmaterias.create'); // Formulario de registro de materia
    Route::post('/administrador/gestionmaterias', [MateriasController::class, 'store'])->name('administrador.gestionmaterias.store'); // Guardar nueva materia
    Route::get('/administrador/gestionmaterias/{id}/edit', [MateriasController::class, 'edit'])->name('administrador.gestionmaterias.edit'); // Formulario de edición de materia
    Route::put('/administrador/gestionmaterias/{id}', [MateriasController::class, 'update'])->name('administrador.gestionmaterias.update'); // Actualizar materia
    Route::delete('/administrador/gestionmaterias/{id}', [MateriasController::class, 'destroy'])->name('administrador.gestionmaterias.destroy'); // Eliminar materia
    Route::get('/administrador/gestionmaterias/{id}/detalles', [MateriasController::class, 'showdetalles'])->name('administrador.gestionmaterias.showdetalles'); // Ver detalles de materia

    // Gestion de Cohortes
    Route::get('/administrador/gestioncohorte', [CohorteController::class, 'index'])->middleware('auth', 'role:administrador')->name('administrador.gestioncohorte');
    Route::get('/administrador/gestioncohorte/create/{id}', [CohorteController::class, 'create'])->middleware('auth', 'role:administrador')->name('administrador.gestioncohorte.create');
    Route::post('/administrador/gestioncohorte', [CohorteController::class, 'store'])->middleware('auth', 'role:administrador')->name('administrador.gestioncohorte.store');
    Route::get('/administrador/gestioncohorte/{id}/edit', [CohorteController::class, 'edit'])->middleware('auth', 'role:administrador')->name('administrador.gestioncohorte.edit');
    Route::get('/administrador/gestioncohorte/{id}/detalles', [CohorteController::class, 'showdetalles'])->middleware('auth', 'role:administrador')->name('administrador.gestioncohorte.showdetalles');
    Route::put('/administrador/gestioncohorte/{id}', [CohorteController::class, 'update'])->middleware('auth', 'role:administrador')->name('administrador.gestioncohorte.update');
    Route::delete('/administrador/gestioncohorte/{id}', [CohorteController::class, 'destroy'])->middleware('auth', 'role:administrador')->name('administrador.gestioncohorte.destroy');

    // Gestión de Secciones
    Route::get('/administrador/gestionseccion', [SeccionController::class, 'index'])->name('administrador.gestionseccion'); // Lista de secciones
    Route::get('/administrador/gestionseccion/{id}/create', [SeccionController::class, 'create'])->name('administrador.gestionseccion.create'); // Formulario de registro de sección
    Route::post('/administrador/gestionseccion', [SeccionController::class, 'store'])->name('administrador.gestionseccion.store'); // Guardar nueva sección
    Route::get('/administrador/gestionseccion/{id}/edit', [SeccionController::class, 'edit'])->name('administrador.gestionseccion.edit'); // Formulario de edición de sección
    Route::put('/administrador/gestionseccion/{id}', [SeccionController::class, 'update'])->name('administrador.gestionseccion.update'); // Actualizar sección
    Route::delete('/administrador/gestionseccion/{id}', [SeccionController::class, 'destroy'])->name('administrador.gestionseccion.destroy'); // Eliminar sección
});
