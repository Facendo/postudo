<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::view('/','inicio')->name('inicio');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/estudiante.php';
require __DIR__.'/pago.php';
require __DIR__.'/profesor.php';
require __DIR__.'/administrador.php';
<<<<<<< HEAD
=======
require __DIR__.'/gestionestu.php';
require __DIR__.'/registroestudiante.php';
>>>>>>> 29d5dcc6144ca1b59191d876c8bce087fe544e12
