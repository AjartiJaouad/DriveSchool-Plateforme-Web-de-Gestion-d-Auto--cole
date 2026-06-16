<?php

use App\Http\Controllers\Admin\CandidatController;
use App\Http\Controllers\Candidat\ReservationController;
use App\Http\Controllers\ProfileController;
use App\Models\Candidat;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\PlageHoraireController;

Route::get('/', function () {
    return view('welcome');
});

// Redirection après connexion selon le rôle
Route::get('/dashboard', function () {
    return match (Auth::user()->role) {
        'admin'    => redirect('/admin/dashboard'),
        'moniteur' => redirect('/moniteur/dashboard'),
        default    => view('dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes ADMIN
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::resource('plages', PlageHoraireController::class)->only(['index', 'store', 'destroy']);
        Route::resource('moniteurs', App\Http\Controllers\Admin\MoniteurController::class)->except(['destroy']);
        Route::resource('candidats', CandidatController::class)->only(['index', 'show']);
        Route::patch('moniteurs/{moniteur}/toggle', [App\Http\Controllers\Admin\MoniteurController::class, 'toggleStatus'])->name('moniteurs.toggle');
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    });

// Routes MONITEUR
Route::middleware(['auth', 'role:moniteur'])
    ->prefix('moniteur')
    ->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\MoniteurDashboardController::class, 'index'])->name('moniteur.dashboard');
        Route::get('/events', [App\Http\Controllers\MoniteurDashboardController::class, 'events'])->name('moniteur.events');
        Route::patch('/seances/{seance}/statut', [App\Http\Controllers\MoniteurDashboardController::class, 'updateStatut'])->name('moniteur.seances.statut');
        Route::post('/seances/{seance}/evaluation', [App\Http\Controllers\MoniteurDashboardController::class, 'storeEvaluation'])->name('moniteur.seances.evaluation');
    });

// Routes CANDIDAT
Route::middleware(['auth', 'role:candidat'])
    ->prefix('candidat')
    ->name('candidat.')
    ->group(function () {
        Route::get('/', [ReservationController::class, 'index'])->name('index');
        Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');

        Route::get('/progression', function () {
            $user = Auth::user();

            $candidat = Candidat::where('user_id', $user->id)
                ->with(['progressionDossier.seances.plageHoraire'])
                ->first();

            $progression = $candidat?->progressionDossier;

            return view('candidat.progression', compact('progression'));
        })->name('progression');
    });

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
