<?php

use Illuminate\Support\Facades\Route;

// Controllers Public
use App\Http\Controllers\FrontendController;

// Controllers Breeze
use App\Http\Controllers\ProfileController;

// Controllers Admin
use App\Http\Controllers\TableauController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AnnonceController;
use App\Http\Controllers\Admin\DepartementController;
use App\Http\Controllers\Admin\PaysController;
use App\Http\Controllers\Admin\BureauMembreController;
use App\Http\Controllers\Admin\MembreController;
use App\Http\Controllers\BureauPublicController;
use App\Http\Controllers\Admin\ActiviteController;
use App\Http\Controllers\Membre\AnnonceMembreController;
use App\Http\Controllers\Membre\NotificationController;
use App\Http\Controllers\Admin\GalerieController;
use App\Http\Controllers\Admin\AdminUserController;

/*
|--------------------------------------------------------------------------
| Routes publiques (vitrine)
|--------------------------------------------------------------------------
*/
Route::get('/', [FrontendController::class, 'accueil'])->name('accueil');

Route::get('/apropos', [FrontendController::class, 'apropos'])->name('apropos');
Route::get('/guideEtudiant', [FrontendController::class, 'guideEtudiant'])->name('guideEtudiant');
Route::get('/bureau', [BureauPublicController::class, 'index'])->name('bureau');

Route::get('/activites', [FrontendController::class, 'activites'])->name('activites.public');

Route::get('/galerie', [FrontendController::class, 'galerie'])->name('galerie');

Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/contact', [FrontendController::class, 'contactStore'])->name('contact.store');

Route::get('/jendouba', fn() => view('jendouba'))->name('jendouba');
Route::get('/faculte', fn() => view('faculte'))->name('faculte');

/*
|--------------------------------------------------------------------------
| Inscription membre (public - uniquement invité)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/inscription', [FrontendController::class, 'inscription'])->name('inscription');
    Route::post('/inscription', [FrontendController::class, 'inscriptionStore'])->name('inscription.store');
});


/*
|--------------------------------------------------------------------------
| Auth Breeze (login, logout, register, reset password, etc.)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';


/*
|--------------------------------------------------------------------------
| Dashboard (Espace membre - Breeze)
|--------------------------------------------------------------------------
| Tu peux le garder comme "espace membre" pour l’instant.
*/


/*
|--------------------------------------------------------------------------
| Profil (Breeze)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

     Route::patch('/profile/photo', [ProfileController::class, 'updatePhoto'])
        ->name('profile.photo.update');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [TableauController::class, 'index'])->name('dashboard');

    Route::get('/annonces', [AnnonceMembreController::class, 'index'])->name('membre.annonces.index');
    Route::get('/annonces/{annonce}', [AnnonceMembreController::class, 'show'])->name('membre.annonces.show');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('membre.notifications.index');
});

/*
|--------------------------------------------------------------------------
| Admin (Back-office)
|--------------------------------------------------------------------------
| Middleware admin : tu vas le définir (AdminMiddleware ou Gate).
*/
Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {

    Route::resource('admins', AdminUserController::class)->except(['show']);
    Route::patch('admins/{admin}/toggle-super', [AdminUserController::class, 'toggleSuper'])
    ->middleware('super_admin')
    ->name('admins.toggleSuper');



        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('departements', DepartementController::class)->except(['show']);
        Route::resource('pays', PaysController::class)
    ->parameters(['pays' => 'pays'])
    ->except(['show']);


        // Membres : inscription publique => pas de create/store ici
        Route::resource('membres', MembreController::class)->only(['index','show','edit','update','destroy']);

        Route::resource('activites', ActiviteController::class)->except(['show']);
        
        Route::resource('bureau', BureauMembreController::class)->except(['show']);
        Route::resource('annonces', AnnonceController::class)->except(['show']);

        Route::resource('galerie', GalerieController::class)
    ->parameters(['galerie' => 'photo'])
    ->except(['show']);

Route::patch('galerie/{photo}/toggle', [GalerieController::class, 'toggle'])
    ->name('galerie.toggle');

    });
