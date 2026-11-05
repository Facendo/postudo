<?php

use App\Http\Controllers\PreinscripcionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Preinscripción (público)
|--------------------------------------------------------------------------
|
| Formulario de preinscripción de la planilla .doc de la coordinación:
| accesible sin sesión y, al enviarlo, se descarga el .docx relleno. La
| gestión (listado, aprobar, rechazar y regenerar) vive en
| routes/administrador.php, en el grupo de doble rol.
|
*/

Route::get('/preinscripcion', [PreinscripcionController::class, 'create'])->name('preinscripcion.create');
Route::post('/preinscripcion', [PreinscripcionController::class, 'store'])->name('preinscripcion.store');
