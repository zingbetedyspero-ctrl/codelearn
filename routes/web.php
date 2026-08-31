<?php

use App\Http\Controllers\AccueilController;
use App\Http\Controllers\ApprenantController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CertificatController;
use App\Http\Controllers\ChapitreController;
use App\Http\Controllers\CourController;
use App\Http\Controllers\CoursCatalogueController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\JournalActiviteController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\TentativeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

Route::get('/', [AccueilController::class, 'index'])->name('accueil');

Route::get('/catalogue', [CoursCatalogueController::class, 'index'])->name('catalogue.index');
Route::get('/catalogue/{cour}', [CoursCatalogueController::class, 'show'])->name('catalogue.show');

Route::get('/certificats/verifier', [CertificatController::class, 'formulaireVerification'])->name('certificats.verifier');
Route::get('/certificats/verifier/resultat', [CertificatController::class, 'verifier'])->name('certificats.verifier.resultat');

Route::get('/tableau-de-bord', function () {
    return auth()->user()->estAdministrateur()
        ? redirect()->route('statistiques.admin')
        : redirect()->route('statistiques.apprenant');
})->middleware('auth')->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/inscription', [RegisterController::class, 'create'])->name('register');
    Route::post('/inscription', [RegisterController::class, 'store']);
    Route::get('/connexion', [LoginController::class, 'create'])->name('login');
    Route::post('/connexion', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/deconnexion', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/mot-de-passe', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/catalogue/{cour}/chapitres/{chapitre}', [CoursCatalogueController::class, 'lireChapitre'])->name('catalogue.chapitre');

    Route::get('/paiements/{cour}', [PaiementController::class, 'initier'])->name('paiements.initier');
    Route::get('/paiements/{cour}/retour', [PaiementController::class, 'retour'])->name('paiements.retour');

    // Module 6 - Passage des tests et tentatives
    Route::get('/evaluations/{evaluation}/tentative', [TentativeController::class, 'create'])->name('tentatives.create');
    Route::post('/evaluations/{evaluation}/tentative', [TentativeController::class, 'store'])->name('tentatives.store');
    Route::get('/tentatives/{tentative}/resultat', [TentativeController::class, 'resultat'])->name('tentatives.resultat');
    Route::get('/mes-tentatives', [TentativeController::class, 'historique'])->name('tentatives.historique');

    // Module 9 - Certificats
    Route::get('/mes-certificats', [CertificatController::class, 'mesCertificats'])->name('certificats.index');
    Route::get('/certificats/{certificat}/telecharger', [CertificatController::class, 'telecharger'])->name('certificats.telecharger');

    // Module 11 - Tableau de bord apprenant
    Route::get('/mes-progres', [StatistiqueController::class, 'apprenant'])->name('statistiques.apprenant');

    // Module 12 - Journalisation anti-fraude (appelé en JS pendant les tentatives)
    Route::post('/journal', [JournalActiviteController::class, 'enregistrer'])->name('journal.enregistrer');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('notifications.show');
});



Route::post('/webhooks/fedapay', [PaiementController::class, 'webhook'])->name('paiements.webhook');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/apprenants', [ApprenantController::class, 'index'])->name('apprenants.index');
        Route::put('/apprenants/{apprenant}/statut', [ApprenantController::class, 'toggleStatut'])->name('apprenants.toggle-statut');
        Route::get('/journal', [JournalActiviteController::class, 'index'])->name('journal.index');
    });

    Route::put('/cours/{cour}/statut', [CourController::class, 'toggleStatut'])->name('cours.toggle-statut');

    Route::resource('categories', CategorieController::class)->except(['show']);
    Route::resource('cours', CourController::class)->except(['show']);

    Route::resource('cours.chapitres', ChapitreController::class)->except(['show'])->shallow();
    Route::post('/chapitres/{chapitre}/monter', [ChapitreController::class, 'monter'])->name('chapitres.monter');
    Route::post('/chapitres/{chapitre}/descendre', [ChapitreController::class, 'descendre'])->name('chapitres.descendre');

    Route::resource('cours.evaluations', EvaluationController::class)->except(['show'])->shallow();
    Route::resource('evaluations.questions', QuestionController::class)->except(['show'])->shallow();
    Route::post('/evaluations/{evaluation}/questions-multiples', [QuestionController::class, 'storeMultiple'])->name('evaluations.questions.store-multiple');

    Route::post('/admin/media/upload', [\App\Http\Controllers\MediaUploadController::class, 'upload'])->name('admin.media.upload');

    // Module 11 - Tableau de bord admin
    Route::get('/statistiques', [StatistiqueController::class, 'admin'])->name('statistiques.admin');
});
