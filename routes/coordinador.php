<?php

use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CoordinadorGeneralController;
use App\Http\Controllers\CoordinadorPostgradoController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\MencionController;
use Illuminate\Support\Facades\Route;

// Rutas del panel de coordinador general (requieren autenticación y rol de 'coordinador_general')
Route::middleware(['auth', 'role:coordinador_general'])->group(function () {

    // Panel principal del coordinador general
    Route::get('/coordinador', [CoordinadorGeneralController::class, 'index'])->name('coordinador.index');

    // Gestión de Estudiantes (trasladada desde el panel del administrador, 2026-10-14)
    Route::get('/coordinador/gestionestudiantes', [EstudianteController::class, 'list'])->name('administrador.gestionestudiantes'); // Lista de estudiantes
    Route::get('/coordinador/gestion_estudiante', [EstudianteController::class, 'list'])->name('estudiante.list'); // Otra ruta para listar estudiantes
    Route::get('/coordinador/registro_estudiante', [EstudianteController::class, 'create'])->name('registro_estudiante.index'); // Formulario de registro de estudiante
    Route::post('/coordinador/registro_estudiante', [EstudianteController::class, 'store'])->name('estudiante.store'); // Guardar nuevo estudiante
    Route::get('/coordinador/gestion_estudiante/{estudiante}/edit', [EstudianteController::class, 'edit'])->name('administrador.gestion_estudiante.edit'); // Formulario de edición de estudiante
    Route::put('/coordinador/gestion_estudiante/{estudiante}', [EstudianteController::class, 'update'])->name('administrador.gestion_estudiante.update'); // Actualizar estudiante
    Route::delete('/coordinador/gestion_estudiante/{estudiante}', [EstudianteController::class, 'destroy'])->name('administrador.gestion_estudiante.destroy'); // Eliminar estudiante
    Route::get('/coordinador/estudiantes_por_postgrado', [EstudianteController::class, 'listByPostgrado'])->name('administrador.estudiantes_por_postgrado'); // Estudiantes por postgrado (antes: por especialidad)

    // Gestión de Áreas (página-hub; el árbol académico es Área -> Postgrado -> Mención)
    Route::get('/coordinador/areas', [AdministradorController::class, 'creacionArea'])->name('administrador.creacion.index'); // Vista para creación de áreas
    Route::post('/coordinador/areas', [AreaController::class, 'store'])->name('administrador.area.store'); // Guardar nueva área
    Route::get('/coordinador/areas/{area}/impacto', [AreaController::class, 'impacto'])->name('administrador.area.impacto'); // Qué se borrará en cascada al eliminar el área
    Route::get('/coordinador/areas/{area}/edit', [AreaController::class, 'edit'])->name('administrador.area.edit'); // Formulario de edición de área
    Route::put('/coordinador/areas/{area}', [AreaController::class, 'update'])->name('administrador.area.update'); // Actualizar área
    Route::delete('/coordinador/areas/{area}', [AreaController::class, 'destroy'])->name('administrador.area.destroy'); // Eliminar área

    // Menciones (dependen de un postgrado)
    // Se permite también el rol administrador: la página de detalles del
    // postgrado vive hoy en el panel del administrador. El coordinador de
    // postgrado solo puede con las de SUS postgrados (lo filtra el controller).
    Route::middleware(['auth', 'role:coordinador_general|administrador|coordinador_postgrado'])->group(function () {
        Route::post('/coordinador/postgrado/{postgrado}/menciones', [MencionController::class, 'store'])->name('administrador.mencion.store'); // Guardar nueva mención
        Route::put('/coordinador/mencion/{mencion}', [MencionController::class, 'update'])->name('administrador.mencion.update'); // Actualizar mención
        Route::delete('/coordinador/mencion/{mencion}', [MencionController::class, 'destroy'])->name('administrador.mencion.destroy'); // Eliminar mención
    });

    // Coordinadores de Postgrado: el coordinador general los crea y les
    // asigna sus postgrados (1 coordinador por postgrado; unique en la pivote).
    // El CRUD disponible hoy es crear + listado: editar y borrar quedan pendientes.
    Route::get('/coordinador/coordinadores_postgrado', [CoordinadorPostgradoController::class, 'index'])->name('coordinador.coordinador_postgrado.index'); // Lista de coordinadores de postgrado
    Route::get('/coordinador/coordinadores_postgrado/create', [CoordinadorPostgradoController::class, 'create'])->name('coordinador.coordinador_postgrado.create'); // Formulario de registro
    Route::post('/coordinador/coordinadores_postgrado', [CoordinadorPostgradoController::class, 'store'])->name('coordinador.coordinador_postgrado.store'); // Guardar coordinador con sus postgrados
});

// Panel del coordinador de postgrado: solo ve SUS postgrados asignados
// (el filtrado por rol vive en el trait FiltrablePorCoordinador).
Route::middleware(['auth', 'role:coordinador_postgrado'])->group(function () {
    Route::get('/coordinador_postgrado', [CoordinadorPostgradoController::class, 'panel'])->name('coordinador_postgrado.index'); // Panel principal del coordinador de postgrado
});
