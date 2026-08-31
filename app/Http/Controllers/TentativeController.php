<?php

namespace App\Http\Controllers;

use App\Jobs\CorrigerTentativeIAJob;
use App\Models\Evaluation;
use App\Models\InscriptionCours;
use App\Models\TentativeEvaluation;
use App\Services\CorrectionIAService;
use App\Services\TentativeCorrectionService;
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
        $contexteCours = $this->contexteCoursPour($evaluation);

        $reponsesData = [];
        $enAttenteIA = false;

        foreach ($questions as $question) {
            $enAttente = false;
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
                // Question ouverte : corrigée par l'IA (Module 7).
                $reponseTexte = $request->input('reponses.' . $question->id, '');

                try {
                    $noteObtenue = app(CorrectionIAService::class)
                        ->corrigerReponseOuverte($question, $reponseTexte, $contexteCours);
                } catch (\Throwable $e) {
                    // IA indisponible : la réponse attend une correction différée (voir Job).
                    $enAttente = true;
                    $enAttenteIA = true;
                    $noteObtenue = null;
                }
            }

            $reponsesData[] = [
                'question_id' => $question->id,
                'reponse' => $reponseTexte,
                'note_obtenue' => $noteObtenue,
                'en_attente_ia' => $enAttente,
            ];
        }

        $numeroTentative = TentativeEvaluation::where('user_id', Auth::id())
            ->where('evaluation_id', $evaluation->id)->count() + 1;

        $tentative = TentativeEvaluation::create([
            'user_id' => Auth::id(),
            'evaluation_id' => $evaluation->id,
            'temps_effectuer' => $tempsEffectue,
            'score' => null,
            'statut' => 'en_attente',
            'numero_tentative' => $numeroTentative,
        ]);

        foreach ($reponsesData as $r) {
            $tentative->reponses()->create([
                'question_id' => $r['question_id'],
                'reponse' => $r['reponse'],
                'note_obtenue' => $r['note_obtenue'],
                'en_attente_ia' => $r['en_attente_ia'],
                'date_reponse' => now(),
            ]);
        }

        session()->forget('tentative_en_cours_' . Auth::id() . '_' . $evaluation->id);

        if ($enAttenteIA) {
            CorrigerTentativeIAJob::dispatch($tentative->id);
        } else {
            app(TentativeCorrectionService::class)->finaliser($tentative);
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

    private function contexteCoursPour(Evaluation $evaluation): string
    {
        if ($evaluation->type_evaluation === 'test_chapitre' && $evaluation->chapitre) {
            return strip_tags($evaluation->chapitre->contenu ?? '');
        }

        // Examen final : contexte = contenu de tous les chapitres du cours, dans l'ordre.
        return $evaluation->cour->chapitres
            ->sortBy('ordre_affichage')
            ->pluck('contenu')
            ->filter()
            ->map(fn ($c) => strip_tags($c))
            ->implode("\n\n");
    }
}
