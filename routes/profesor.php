<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstudianteController;
use App\Http\Middleware\CheckRole;

Route::view(("/profesor"), 'profesor.index')->name('profesor.index');