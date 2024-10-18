<?php

use App\Livewire\Dashboard;
use App\Livewire\ReleveNotes;
use App\Livewire\ListeEtudiants;
use App\Livewire\ReleveNotesPdf;
use App\Livewire\ImportEtudiants;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReleveNotesController;

// Route racine - Redirection basée sur l'authentification
Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : redirect('/login');
});

// Routes authentifiées
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    // Gestion des étudiants
    Route::prefix('etudiants')->group(function () {
        Route::get('/', ListeEtudiants::class)->name('etudiants');
    });

    // Relevés de notes
    Route::prefix('releve-notes')->group(function () {
        Route::get('/releve-notes/{id}', ReleveNotes::class)->name('releve-notes');
        Route::get('/releve-notes/{id}/pdf', ReleveNotesPdf::class)->name('releve-notes.pdf');
    });

    // Profil utilisateur
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});


// Routes d'authentification (login, register, etc.)
require __DIR__.'/auth.php';
Route::redirect('/', '/login');
Route::redirect('/register', '/login');
