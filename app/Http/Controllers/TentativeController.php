<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\InscriptionCours;
use App\Models\TentativeEvaluation;
use App\Notifications\ChapitreDebloqueNotification;
use App\Notifications\CertificatDelivreNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TentativeController extends Controller
{
    public function create(Evaluation $evaluation)
    {
        $this->verifierAcces($evaluation);

        // RG08 : limiter les connexions simultanées à une même session d'examen.
        $cle = 'tentative_en_cours_' . Auth::id() . '_' . $evaluation->id;
        if (session()->has($cle)) {
            return redirect()->route('catalogue.show', $evaluation->cour)
                ->with('error', 'Vous avez déjà une tentative en cours pour cette évaluation.');
        }
        session([$cle => true]);

        $questions = $evaluation->questions()->with('optionsReponse')->get();

        return view('evaluations.tentative', compact('evaluation', 'questions'));
    }

    public function store(Request $request, Evaluation $evaluation)
    {
        $this->verifierAcces($evaluation);

        $questions = $evaluation->questions()->with('optionsReponse')->get();
        $debut = (int) $request->input('debut', now()->timestamp);
        $tempsEffectue = max(0, now()->timestamp - $debut);

        $totalBareme = 0;
        $totalObtenu = 0;
        $reponsesData = [];

        foreach ($questions as $question) {
            $totalBareme += $question->bareme;
            $noteObtenue = 0;
            $reponseTexte = '';

            if ($question->estQcm()) {
                $selectionnees = $request->input('reponses.' . $question->id, []);
                $correctesIds = $question->optionsReponse->where('is_correct', true)->pluck('id')->map(fn ($v) => (string) $v)->sort()->values();
                $selectionneesIds = collect($selectionnees)->map(fn ($v) => (string) $v)->sort()->values();
                $estCorrecte = $correctesIds->isNotEmpty() && $correctesIds->toArray() === $selectionneesIds->toArray();
                $noteObtenue = $estCorrecte ? $question->bareme : 0;
                $reponseTexte = implode(',', $selectionnees);
            } else {
                // Question ouverte : pas de correction automatique (nécessiterait le Module 7 - IA,
                // marqué "non pertinent" dans le planning). La réponse est enregistrée pour correction
                // manuelle ultérieure ; elle ne compte pour l'instant pas dans le score.
                $reponseTexte = $request->input('reponses.' . $question->id, '');
                $noteObtenue = 0;
            }

            $totalObtenu += $noteObtenue;

            $reponsesData[] = [
                'question_id' => $question->id,
                'reponse' => $reponseTexte,
                'note_obtenue' => $noteObtenue,
            ];
        }

        $scorePourcent = $totalBareme > 0 ? round(($totalObtenu / $totalBareme) * 100, 2) : 0;
        $reussi = $scorePourcent >= $evaluation->seuil_reussite;

        $numeroTentative = TentativeEvaluation::where('user_id', Auth::id())
            ->where('evaluation_id', $evaluation->id)->count() + 1;

        $tentative = TentativeEvaluation::create([
            'user_id' => Auth::id(),
            'evaluation_id' => $evaluation->id,
            'temps_effectuer' => $tempsEffectue,
            'score' => $scorePourcent,
            'statut' => $reussi ? 'reussi' : 'echoue',
            'numero_tentative' => $numeroTentative,
        ]);

        foreach ($reponsesData as $r) {
            $tentative->reponses()->create([
                'question_id' => $r['question_id'],
                'reponse' => $r['reponse'],
                'note_obtenue' => $r['note_obtenue'],
                'date_reponse' => now(),
            ]);
        }

        session()->forget('tentative_en_cours_' . Auth::id() . '_' . $evaluation->id);

        if ($reussi) {
            $this->traiterReussite($evaluation);
        }

        return redirect()->route('tentatives.resultat', $tentative);
    }

    public function resultat(TentativeEvaluation $tentative)
    {
        if ($tentative->user_id !== Auth::id()) {
            abort(403);
        }

        $tentative->load(['evaluation.cour', 'reponses.question']);

        return view('evaluations.resultat', compact('tentative'));
    }

    public function historique()
    {
        $tentatives = TentativeEvaluation::where('user_id', Auth::id())
            ->with('evaluation.cour')->latest()->paginate(15);

        return view('evaluations.historique', compact('tentatives'));
    }

    private function verifierAcces(Evaluation $evaluation): void
    {
        $cour = $evaluation->cour;

        if ($evaluation->type_evaluation === 'test_chapitre') {
            $chapitre = $evaluation->chapitre;

            if ($chapitre->ordre_affichage === 1) {
                return; // introduction gratuite, accessible à tout apprenant connecté
            }

            $inscription = $this->inscriptionActive($cour);

            if (! $inscription || $chapitre->ordre_affichage > $inscription->chapitreDebloqueJusqua()) {
                abort(403, "Ce chapitre n'est pas encore débloqué.");
            }

            return;
        }

        // RG04 : l'examen final n'est accessible qu'après validation de tous les chapitres.
        $inscription = $this->inscriptionActive($cour);
        $totalChapitres = $cour->chapitres()->count();

        if (! $inscription || $inscription->chapitreDebloqueJusqua() <= $totalChapitres) {
            abort(403, "L'examen final n'est accessible qu'après validation de tous les chapitres.");
        }
    }

    private function inscriptionActive($cour): ?InscriptionCours
    {
        return InscriptionCours::whereHas('payement', function ($q) use ($cour) {
            $q->where('user_id', Auth::id())->where('cour_id', $cour->id)->where('statut_paiement', 'approved');
        })->first();
    }

    private function traiterReussite(Evaluation $evaluation): void
    {
        $cour = $evaluation->cour;
        $inscription = $this->inscriptionActive($cour);

        if (! $inscription) {
            return; // cas du test du chapitre 1 (gratuit) : rien à débloquer, sert juste de porte au paiement
        }

        if ($evaluation->type_evaluation === 'test_chapitre') {
            $chapitre = $evaluation->chapitre;

            if ($chapitre && $chapitre->ordre_affichage === $inscription->chapitreDebloqueJusqua()) {
                $inscription->increment('progression');

                $chapitreSuivant = $cour->chapitres()->where('ordre_affichage', $inscription->progression)->first();

                if ($chapitreSuivant) {
                    Auth::user()->notify(new ChapitreDebloqueNotification($cour, $chapitreSuivant));
                }
            }

            return;
        }

        // Examen final réussi
        $inscription->update(['statut' => 'termine']);

        $certificat = app(CertificatController::class)->genererPour($inscription, $evaluation);

        Auth::user()->notify(new CertificatDelivreNotification($cour->titre, $certificat->code_verification));
    }
}