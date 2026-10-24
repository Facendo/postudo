<?php

use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\ArancelesController;
use App\Http\Controllers\CohorteController;
use App\Http\Controllers\CoordinadorGeneralController;
use App\Http\Controllers\EgresoController;
use App\Http\Controllers\PostgradoController;
use App\Http\Controllers\PreinscripcionController;
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

    // Aranceles: el administrador actualiza el texto de la normativa
    // que muestra el modal de la portada (texto crudo en aranceles.json).
    Route::get('/administrador/aranceles', [ArancelesController::class, 'edit'])->name('administrador.aranceles.edit');
    Route::post('/administrador/aranceles', [ArancelesController::class, 'update'])->name('administrador.aranceles.update');

    // NOTA (2026-10-14): la gestión de estudiantes y la creación de áreas
    // se trasladó al panel del coordinador general (ver routes/coordinador.php).
    // El panel del administrador se enfoca en pagos, asuntos y finanzas.

    // NOTA (2026-10-21): se eliminaron por completo la gestión de profesores,
    // materias y secciones (y el rol profesor): el árbol académico se quedó
    // en Área -> Postgrado -> Mención -> Cohorte.

    // NOTA (2026-10-16): la gestión de postgrados y cohortes se trasladó al
    // grupo de doble rol del final de este archivo (administrador|coordinador_general).
});

// Gestión de Postgrados y Cohortes: accesible también para el coordinador
// general, porque los postgrados (y sus cohortes y menciones) son parte del
// árbol académico Área -> Postgrado -> Mención que administra el coordinador.
// El coordinador de postgrado entra por las mismas URLs, pero solo a SUS
// postgrados asignados: cada controller filtra con el trait
// FiltrablePorCoordinador (y crear/editar/borrar postgrados le queda
// prohibido ahí mismo).
// Mismas URLs y nombres de ruta que antes, solo cambia el rol permitido.
Route::middleware(['auth', 'role:administrador|coordinador_general|coordinador_postgrado'])->group(function () {

    // Gestión de Postgrados
    Route::get('/administrador/gestion_postgrado', [PostgradoController::class, 'index'])->name('administrador.gestion_postgrado'); // Lista de postgrados
    Route::get('/administrador/gestion_postgrado/create', [PostgradoController::class, 'create'])->name('administrador.gestion_postgrado.create'); // Formulario de registro de postgrado
    Route::post('/administrador/gestion_postgrado', [PostgradoController::class, 'store'])->name('administrador.gestion_postgrado.store'); // Guardar nuevo postgrado
    Route::get('/administrador/gestion_postgrado/{id}/edit', [PostgradoController::class, 'edit'])->name('administrador.gestion_postgrado.edit'); // Formulario de edición de postgrado
    Route::get('/administrador/gestion_postgrado/{id}/detalles', [PostgradoController::class, 'showdetalles'])->name('administrador.gestion_postgrado.showdetalles'); // Ver detalles de postgrado
    Route::get('/administrador/gestion_postgrado/{id}/impacto', [PostgradoController::class, 'impacto'])->name('administrador.gestion_postgrado.impacto'); // Qué se borrará en cascada al eliminar el postgrado
    Route::put('/administrador/gestion_postgrado/{id}', [PostgradoController::class, 'update'])->name('administrador.gestion_postgrado.update'); // Actualizar postgrado
    Route::delete('/administrador/gestion_postgrado/{id}', [PostgradoController::class, 'destroy'])->name('administrador.gestion_postgrado.destroy'); // Eliminar postgrado

    // Gestión de Cohortes (desde «Detalles del Postgrado» o desde el listado)
    Route::get('/administrador/gestioncohorte', [CohorteController::class, 'index'])->name('administrador.gestioncohorte'); // Lista de cohortes
    Route::get('/administrador/gestioncohorte/create/{postgrado?}', [CohorteController::class, 'create'])->name('administrador.gestioncohorte.create'); // Formulario de registro de cohorte (mención obligatoria)
    Route::post('/administrador/gestioncohorte', [CohorteController::class, 'store'])->name('administrador.gestioncohorte.store'); // Guardar nueva cohorte
    Route::get('/administrador/gestioncohorte/{id}/edit', [CohorteController::class, 'edit'])->name('administrador.gestioncohorte.edit'); // Formulario de edición de cohorte
    Route::get('/administrador/gestioncohorte/{id}/detalles', [CohorteController::class, 'showdetalles'])->name('administrador.gestioncohorte.showdetalles'); // Ver detalles de cohorte
    Route::put('/administrador/gestioncohorte/{id}', [CohorteController::class, 'update'])->name('administrador.gestioncohorte.update'); // Actualizar cohorte
    Route::delete('/administrador/gestioncohorte/{id}', [CohorteController::class, 'destroy'])->name('administrador.gestioncohorte.destroy'); // Eliminar cohorte
});

// Gestión de preinscripciones (2026-10-21): las revisan y aprueban el
// administrador y el coordinador general. Aprobar solo sella la
// preinscripción (habilitará al aspirante para la planilla de inscripción);
// NO crea usuarios ni filas en 'estudiantes'.
Route::middleware(['auth', 'role:administrador|coordinador_general'])->group(function () {
    Route::get('/administrador/preinscripciones', [PreinscripcionController::class, 'index'])->name('administrador.preinscripciones.index'); // Listado de preinscripciones
    Route::get('/administrador/preinscripciones/{preinscripcion}/descarga', [PreinscripcionController::class, 'descarga'])->name('administrador.preinscripciones.descarga'); // Regenerar y descargar el .docx
    Route::post('/administrador/preinscripciones/{preinscripcion}/aprobar', [PreinscripcionController::class, 'aprobar'])->name('administrador.preinscripciones.aprobar'); // Aprobar preinscripción
    Route::post('/administrador/preinscripciones/{preinscripcion}/rechazar', [PreinscripcionController::class, 'rechazar'])->name('administrador.preinscripciones.rechazar'); // Rechazar preinscripción
});
