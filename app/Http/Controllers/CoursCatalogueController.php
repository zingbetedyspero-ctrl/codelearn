<?php

namespace App\Http\Controllers;

use App\Models\Chapitre;
use App\Models\Cour;
use App\Models\InscriptionCours;
use Illuminate\Support\Facades\Auth;

class CoursCatalogueController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Categorie::whereHas('cours', function ($q) {
            $q->where('statut', 'publie');
        })->with(['cours' => function ($q) {
            $q->where('statut', 'publie');
        }])->get();

        $coursSansCategorie = Cour::where('statut', 'publie')->whereNull('category_id')->get();

        return view('catalogue.index', compact('categories', 'coursSansCategorie'));
    }

    public function show(Cour $cour)
    {
        if (! $cour->estPublie()) {
            abort(404);
        }

        $chapitres = $cour->chapitres;
        $evaluations = $cour->evaluations()->get();

        $inscription = Auth::check()
            ? InscriptionCours::whereHas('payement', function ($q) use ($cour) {
                $q->where('user_id', Auth::id())->where('cour_id', $cour->id)->where('statut_paiement', 'approved');
            })->first()
            : null;

        $chapitreDebloqueJusqua = $inscription ? $inscription->chapitreDebloqueJusqua() : 1;
        $examenAccessible = $inscription && $chapitreDebloqueJusqua > $chapitres->count();
        $examenFinal = $evaluations->firstWhere('type_evaluation', 'examen_final');
        $nbEtudiants = $cour->payements()->where('statut_paiement', 'approved')->count();
        $dejaAchete = $inscription !== null;

        return view('catalogue.show', compact(
            'cour', 'chapitres', 'evaluations', 'chapitreDebloqueJusqua', 'examenAccessible', 'examenFinal', 'nbEtudiants', 'dejaAchete'
        ));
    }

    public function lireChapitre(Cour $cour, Chapitre $chapitre)
    {
        if (! $cour->estPublie() || $chapitre->cour_id !== $cour->id) {
            abort(404);
        }

        $inscription = InscriptionCours::whereHas('payement', function ($q) use ($cour) {
            $q->where('user_id', Auth::id())->where('cour_id', $cour->id)->where('statut_paiement', 'approved');
        })->first();

        $accesComplet = $inscription !== null;

        // Le test de chaque chapitre (bouton "Chapitre terminé") — optionnel pour le chapitre 1 (RG01),
        // obligatoire pour débloquer la suite au-delà.
        $evaluationChapitre = $chapitre->evaluations()->where('type_evaluation', 'test_chapitre')->first();

        if ($chapitre->ordre_affichage === 1) {
            $chapitreSuivant = $cour->chapitres()->where('ordre_affichage', 2)->first();

            return view('catalogue.chapitre', [
                'cour' => $cour,
                'chapitre' => $chapitre,
                'estIntroduction' => true,
                'evaluationChapitre' => $evaluationChapitre,
                'accesComplet' => $accesComplet,
                'chapitreSuivant' => $chapitreSuivant,
            ]);
        }

        if (! $inscription || $chapitre->ordre_affichage > $inscription->chapitreDebloqueJusqua()) {
            abort(403, "Ce chapitre n'est pas encore débloqué. Validez les chapitres précédents pour continuer.");
        }

        return view('catalogue.chapitre', [
            'cour' => $cour,
            'chapitre' => $chapitre,
            'estIntroduction' => false,
            'evaluationChapitre' => $evaluationChapitre,
            'accesComplet' => $accesComplet,
            'chapitreSuivant' => null,
        ]);
    }
}