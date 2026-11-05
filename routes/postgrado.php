<?php

use App\Http\Controllers\PostgradoController;
use Illuminate\Support\Facades\Route;

// Catálogo público de postgrados: Áreas -> Postgrados -> Menciones
Route::get('/postgrado', [PostgradoController::class, 'portal'])->name('postgrado.index');
