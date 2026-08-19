<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Chapitre;
use App\Models\Cour;
use App\Models\InscriptionCours;
use App\Models\Payement;
use App\Models\TentativeEvaluation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StatistiqueController extends Controller
{
    public function apprenant()
    {
        $inscriptions = InscriptionCours::whereHas('payement', function ($q) {
            $q->where('user_id', Auth::id());
        })->with('payement.cour')->get();

        $paiementsEnAttente = Payement::where('user_id', Auth::id())->where('statut_paiement', 'pending')->with('cour')->get();

        $tentatives = TentativeEvaluation::where('user_id', Auth::id())
            ->with('evaluation.cour')->latest()->take(5)->get();

        return view('statistiques.apprenant', compact('inscriptions', 'paiementsEnAttente', 'tentatives'));
    }

    // Page "Inventaire" administrateur : vue globale complète de la plateforme.
    public function admin()
    {
        $nbCours = Cour::count();
        $nbCoursPublies = Cour::where('statut', 'publie')->count();
        $nbChapitres = Chapitre::count();
        $nbCategories = Categorie::count();
        $nbApprenants = User::where('role', 'apprenant')->count();
        $nbInscriptions = InscriptionCours::count();
        $nbCoursSuivis = InscriptionCours::distinct('payement_id')->count('payement_id');

        $revenuTotal = Payement::where('statut_paiement', 'approved')->sum('montant');

        $revenuParCategorie = Categorie::with(['cours.payements' => function ($q) {
            $q->where('statut_paiement', 'approved');
        }])->get()->map(function ($categorie) {
            $categorie->revenu = $categorie->cours->sum(function ($cours) {
                return $cours->payements->sum('montant');
            });

            return $categorie;
        });

        $nbTentatives = TentativeEvaluation::count();
        $nbReussies = TentativeEvaluation::where('statut', 'reussi')->count();
        $tauxReussite = $nbTentatives > 0 ? round($nbReussies / $nbTentatives * 100, 1) : 0;

        $coursParRevenu = Cour::withSum(['payements' => function ($q) {
            $q->where('statut_paiement', 'approved');
        }], 'montant')->orderByDesc('payements_sum_montant')->take(5)->get();

        $coursPlusSuivis = Cour::withCount(['payements' => function ($q) {
            $q->where('statut_paiement', 'approved');
        }])->orderByDesc('payements_count')->take(5)->get();

        $listeCours = Cour::with('categorie')->withCount('chapitres')->orderByDesc('created_at')->get();
        $repartitionCategories = Categorie::withCount('cours')->get();

        return view('statistiques.admin', compact(
            'nbCours', 'nbCoursPublies', 'nbChapitres', 'nbCategories', 'nbApprenants',
            'nbInscriptions', 'nbCoursSuivis', 'revenuTotal', 'revenuParCategorie',
            'nbTentatives', 'nbReussies', 'tauxReussite', 'coursParRevenu', 'coursPlusSuivis',
            'listeCours', 'repartitionCategories'
        ));
    }
}
