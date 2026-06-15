<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PlageHoraireController;
Route::get('/', function () {
    return view('welcome');
});

// Redirection après connexion selon le rôle
Route::get('/dashboard', function () {
    return match (auth()->user()->role) {
        'admin'    => redirect('/admin/dashboard'),
        'moniteur' => redirect('/moniteur/dashboard'),
        default    => view('dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes ADMIN
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {
Route::resource('plages', PlageHoraireController::class)->only(['index', 'store', 'destroy']);
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
        Route::resource('moniteurs', App\Http\Controllers\Admin\MoniteurController::class)->except(['destroy']);
Route::patch('moniteurs/{moniteur}/toggle', [App\Http\Controllers\Admin\MoniteurController::class, 'toggleStatus'])->name('moniteurs.toggle');
    });


Route::middleware(['auth', 'role:moniteur'])
    ->prefix('moniteur')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('moniteur.dashboard');
        })->name('moniteur.dashboard');


    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
