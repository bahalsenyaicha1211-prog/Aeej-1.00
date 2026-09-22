<?php

/*
|--------------------------------------------------------------------------
| Routes web de l'application AEEJ
|--------------------------------------------------------------------------
|
| Toutes les URLs du site (public, espace membre, back-office admin,
| espace trésorerie) sont définies ici. Le fichier est découpé en grandes
| sections, dans l'ordre où un visiteur les rencontre : d'abord la vitrine
| publique, puis l'authentification, l'espace membre, le back-office admin,
| et enfin l'espace trésorerie (qui est un sous-ensemble de l'espace membre
| réservé aux rôles financiers).
|
| Convention de nommage des routes : "admin.xxx" pour le back-office,
| "membre.xxx" pour l'espace membre, "tresorerie.xxx" pour la trésorerie.
| Les contrôleurs suivent le même découpage dans app/Http/Controllers/
| (dossiers Admin/, Membre/, Tresorerie/).
|
*/

use Illuminate\Support\Facades\Route;

// --- Contrôleurs de la vitrine publique (pages accessibles sans compte) ---
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\BureauPublicController;

// --- Profil du compte connecté (scaffolding Laravel Breeze) ---
use App\Http\Controllers\ProfileController;

// --- Espace membre (une fois connecté et approuvé) ---
use App\Http\Controllers\TableauController;
use App\Http\Controllers\Membre\AnnonceMembreController;
use App\Http\Controllers\Membre\NotificationController;
use App\Http\Controllers\Membre\CotisationMembreController;

// --- Back-office admin (gestion du contenu du site) ---
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AnnonceController;
use App\Http\Controllers\Admin\DepartementController;
use App\Http\Controllers\Admin\PaysController;
use App\Http\Controllers\Admin\BureauMembreController;
use App\Http\Controllers\Admin\MembreController;
use App\Http\Controllers\Admin\ActiviteController;
use App\Http\Controllers\Admin\GalerieController;
use App\Http\Controllers\Admin\HeroImageController;
use App\Http\Controllers\Admin\PartenaireController;
use App\Http\Controllers\Admin\ContactPersonController;
use App\Http\Controllers\Admin\PartnerCategoryController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\TresorerieCompteController;

// --- Espace trésorerie (cotisations, caisse, dépenses) ---
use App\Http\Controllers\Tresorerie\CotisationController;
use App\Http\Controllers\Tresorerie\CotisationConfigController;
use App\Http\Controllers\Tresorerie\CotisationVolontaireController;
use App\Http\Controllers\Tresorerie\CaisseController;
use App\Http\Controllers\Tresorerie\DepenseController;

/*
|--------------------------------------------------------------------------
| 1. Vitrine publique
|--------------------------------------------------------------------------
| Pages accessibles à tout visiteur, sans compte. Aucun middleware d'accès
| ici (à part le throttle anti-spam sur les deux formulaires publics).
*/
Route::get('/', [FrontendController::class, 'accueil'])->name('accueil');

Route::get('/apropos', [FrontendController::class, 'apropos'])->name('apropos');
Route::get('/guideEtudiant', [FrontendController::class, 'guideEtudiant'])->name('guideEtudiant');
Route::get('/bureau', [BureauPublicController::class, 'index'])->name('bureau');

Route::get('/activites', [FrontendController::class, 'activites'])->name('activites.public');

Route::get('/galerie', [FrontendController::class, 'galerie'])->name('galerie');

Route::get('/partenaires', [FrontendController::class, 'partenaires'])->name('partenaires');

Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/contact', [FrontendController::class, 'contactStore'])
    ->middleware('throttle:5,1') // anti-spam : 5 envois max par minute et par IP
    ->name('contact.store');

Route::get('/jendouba', [FrontendController::class, 'jendouba'])->name('jendouba');
Route::get('/faculte', [FrontendController::class, 'faculte'])->name('faculte');

/*
|--------------------------------------------------------------------------
| 2. Inscription d'un nouveau membre
|--------------------------------------------------------------------------
| Réservée aux visiteurs non connectés (middleware "guest") : un membre
| déjà inscrit ne doit pas pouvoir recréer un compte par cette voie.
*/
Route::middleware('guest')->group(function () {
    Route::get('/inscription', [FrontendController::class, 'inscription'])->name('inscription');
    Route::post('/inscription', [FrontendController::class, 'inscriptionStore'])
        ->middleware('throttle:5,1') // anti-spam : 5 tentatives max par minute et par IP
        ->name('inscription.store');
});

/*
|--------------------------------------------------------------------------
| 3. Authentification (Laravel Breeze)
|--------------------------------------------------------------------------
| Connexion, déconnexion, mot de passe oublié, vérification d'e-mail...
| Toutes les routes standard Breeze sont définies dans routes/auth.php.
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| 4. Profil du compte connecté
|--------------------------------------------------------------------------
| Modifier ses informations, sa photo, son mot de passe, supprimer son
| compte. Accessible dès la connexion (pas besoin d'être "approuvé" : un
| membre en attente de validation doit pouvoir gérer son propre profil).
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/coordonnees', [ProfileController::class, 'updateCoordonnees'])->name('profile.coordonnees.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::patch('/profile/photo', [ProfileController::class, 'updatePhoto'])
        ->middleware('throttle:12,1') // anti-spam : 12 envois de photo max par minute
        ->name('profile.photo.update');

    // Écran affiché tant qu'un admin n'a pas validé l'inscription du membre.
    Route::view('/compte/en-attente', 'auth.pending-approval')->name('account.pending');
});

/*
|--------------------------------------------------------------------------
| 5. Espace membre
|--------------------------------------------------------------------------
| Tableau de bord, annonces, cotisations personnelles... Accessible une
| fois connecté ("auth"), e-mail vérifié ("verified") et compte validé par
| un admin ("approved"). C'est le cœur de l'espace réservé aux membres.
*/
Route::middleware(['auth', 'verified', 'approved'])->group(function () {
    Route::get('/dashboard', [TableauController::class, 'index'])->name('dashboard');

    Route::get('/annonces', [AnnonceMembreController::class, 'index'])->name('membre.annonces.index');
    Route::get('/annonces/{annonce}', [AnnonceMembreController::class, 'show'])->name('membre.annonces.show');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('membre.notifications.index');

    Route::get('/ma-cotisation', [CotisationMembreController::class, 'index'])->name('membre.cotisations.index');
});

/*
|--------------------------------------------------------------------------
| 6. Back-office admin
|--------------------------------------------------------------------------
| Toutes les URLs commencent par /admin et exigent d'être connecté avec un
| compte administrateur (middleware "admin" -> AdminMiddleware, vérifie
| $user->is_admin). Un sous-groupe est en plus réservé au super-admin
| (middleware "super_admin" -> $user->is_super_admin) : la gestion des
| comptes admins eux-mêmes et l'attribution des rôles trésorerie, car ce
| sont des actions qui donnent un pouvoir équivalent à celui d'un admin.
*/
Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {

        // -- Réservé au super-admin : gestion des comptes admins --
        // (créer/éditer un admin revient à s'octroyer les mêmes privilèges)
        Route::middleware('super_admin')->group(function () {
            Route::resource('admins', AdminUserController::class)->except(['show']);
            Route::patch('admins/{admin}/toggle-super', [AdminUserController::class, 'toggleSuper'])
                ->name('admins.toggleSuper');
        });

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // -- Référentiels (listes utilisées ailleurs dans le site) --
        Route::resource('departements', DepartementController::class)->except(['show']);
        Route::resource('pays', PaysController::class)
            ->parameters(['pays' => 'pays']) // sans ça, Laravel met {pay} (singulier auto de "pays") au lieu de {pays}
            ->except(['show']);

        // -- Membres : l'inscription se fait sur la vitrine publique, pas de create/store ici --
        Route::resource('membres', MembreController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
        Route::patch('membres/{membre}/approuver', [MembreController::class, 'approve'])->name('membres.approve');

        // -- Contenu du site --
        Route::resource('activites', ActiviteController::class)->except(['show']);
        Route::resource('bureau', BureauMembreController::class)->except(['show']);
        Route::resource('annonces', AnnonceController::class)->except(['show']);

        // Galerie photo (page publique « Galerie »)
        Route::resource('galerie', GalerieController::class)
            ->parameters(['galerie' => 'photo'])
            ->except(['show']);
        Route::patch('galerie/{photo}/toggle', [GalerieController::class, 'toggle'])
            ->name('galerie.toggle');

        // Photos du diaporama de la page d'accueil
        Route::resource('hero-images', HeroImageController::class)
            ->parameters(['hero-images' => 'heroImage'])
            ->except(['show']);
        Route::patch('hero-images/{heroImage}/toggle', [HeroImageController::class, 'toggle'])
            ->name('hero-images.toggle');

        // Partenaires (page publique « Nos partenaires »)
        Route::resource('partenaires', PartenaireController::class)
            ->parameters(['partenaires' => 'partenaire'])
            ->except(['show']);
        Route::patch('partenaires/{partenaire}/toggle', [PartenaireController::class, 'toggle'])
            ->name('partenaires.toggle');

        // Catégories de partenaires : gérées en ligne depuis la page « Partenaires »
        Route::post('partenaires-categories', [PartnerCategoryController::class, 'store'])
            ->name('partenaires-categories.store');
        Route::patch('partenaires-categories/{categorie}', [PartnerCategoryController::class, 'update'])
            ->name('partenaires-categories.update');
        Route::delete('partenaires-categories/{categorie}', [PartnerCategoryController::class, 'destroy'])
            ->name('partenaires-categories.destroy');

        // Personnes à contacter (page publique « Contact »)
        Route::resource('contacts', ContactPersonController::class)
            ->parameters(['contacts' => 'contact'])
            ->except(['show']);
        Route::patch('contacts/{contact}/toggle', [ContactPersonController::class, 'toggle'])
            ->name('contacts.toggle');

        // Messages reçus via le formulaire de contact public
        Route::resource('messages', ContactMessageController::class)
            ->only(['index', 'show', 'destroy']);

        // Attribution des rôles trésorerie (trésorier / chef trésorier / commissaire)
        // -- réservé au super-admin, comme la gestion des comptes admins ci-dessus.
        Route::resource('tresorerie-comptes', TresorerieCompteController::class)
            ->parameters(['tresorerie-comptes' => 'tresorerie_compte'])
            ->except(['show'])
            ->middleware('super_admin');
    });

/*
|--------------------------------------------------------------------------
| 7. Espace trésorerie
|--------------------------------------------------------------------------
| Sous-espace de l'espace membre (URLs /tresorerie/...), réservé aux
| membres ayant un rôle financier. Le middleware "tresorerie_area" exige
| d'être trésorier OU commissaire aux comptes ; chaque route ajoute en plus
| son propre middleware de rôle précis :
|   - "tresorier"       : trésorier ou chef trésorier
|   - "chef_tresorier"  : chef trésorier uniquement (montants, cotisations
|                         volontaires configurées)
|   - "commissaire"     : commissaire aux comptes uniquement (dépenses)
|   - "caisse_access"   : chef trésorier ou commissaire (vue d'ensemble)
| Ces rôles sont des colonnes booléennes sur le compte (is_tresorier,
| is_chef_tresorier, is_commissaire_comptes), attribuées par le
| super-admin depuis /admin/tresorerie-comptes (section 6 ci-dessus).
*/
Route::prefix('tresorerie')
    ->middleware(['auth', 'verified', 'approved', 'tresorerie_area'])
    ->name('tresorerie.')
    ->group(function () {

        // Historique : les fonctionnalités trésorerie sont désormais accessibles
        // directement depuis l'espace membre (/dashboard). On garde ce nom de
        // route pour ne pas casser d'éventuels liens déjà enregistrés.
        Route::get('/', fn () => redirect()->route('dashboard'))->name('dashboard');

        // -- Cotisation annuelle obligatoire --
        Route::resource('cotisations', CotisationController::class)
            ->middleware('tresorier')
            ->except(['show', 'destroy']);
        Route::delete('cotisations/{cotisation}', [CotisationController::class, 'destroy'])
            ->middleware('tresorier')
            ->name('cotisations.destroy');

        // -- Cotisations volontaires (activités optionnelles : camping, sorties...) --
        // Mêmes règles d'accès que les cotisations annuelles, regroupées sous le
        // même écran « Nouveau paiement » / « Cotisations » via un onglet.
        Route::post('cotisations-volontaires', [CotisationVolontaireController::class, 'store'])
            ->middleware('tresorier')
            ->name('cotisations-volontaires.store');
        Route::get('cotisations-volontaires/{volontaire}/edit', [CotisationVolontaireController::class, 'edit'])
            ->middleware('tresorier')
            ->name('cotisations-volontaires.edit');
        Route::put('cotisations-volontaires/{volontaire}', [CotisationVolontaireController::class, 'update'])
            ->middleware('tresorier')
            ->name('cotisations-volontaires.update');
        Route::delete('cotisations-volontaires/{volontaire}', [CotisationVolontaireController::class, 'destroy'])
            ->middleware('tresorier')
            ->name('cotisations-volontaires.destroy');

        // -- Configuration des montants (annuel + cotisations volontaires) --
        Route::get('config-montants', [CotisationConfigController::class, 'edit'])
            ->middleware('chef_tresorier')
            ->name('config.edit');
        Route::post('config-montants', [CotisationConfigController::class, 'update'])
            ->middleware('chef_tresorier')
            ->name('config.update');
        Route::delete('config-montants/{config}', [CotisationConfigController::class, 'destroy'])
            ->middleware('chef_tresorier')
            ->name('config.destroy');
        Route::post('config-montants/types', [CotisationConfigController::class, 'storeType'])
            ->middleware('chef_tresorier')
            ->name('config.types.store');
        Route::patch('config-montants/types/{type}/toggle', [CotisationConfigController::class, 'toggleType'])
            ->middleware('chef_tresorier')
            ->name('config.types.toggle');
        Route::delete('config-montants/types/{type}', [CotisationConfigController::class, 'destroyType'])
            ->middleware('chef_tresorier')
            ->name('config.types.destroy');

        // -- Caisse (vue d'ensemble des entrées/sorties) --
        Route::get('caisse', [CaisseController::class, 'index'])
            ->middleware('caisse_access')
            ->name('caisse.index');

        // -- Dépenses et rapport financier --
        Route::resource('depenses', DepenseController::class)
            ->middleware('commissaire')
            ->except(['show']);
        Route::get('depenses-rapport/pdf', [DepenseController::class, 'rapportPdf'])
            ->middleware('commissaire')
            ->name('depenses.rapport-pdf');
    });
