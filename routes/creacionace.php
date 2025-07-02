<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::view(("/creacionace"), 'administrador.creacionace')->name('administrador.creacion.index');