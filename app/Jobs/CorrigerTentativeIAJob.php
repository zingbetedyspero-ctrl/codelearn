<?php

namespace App\Jobs;

use App\Models\TentativeEvaluation;
use App\Services\CorrectionIAService;
use App\Services\TentativeCorrectionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CorrigerTentativeIAJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 10;
    public array $backoff = [30, 60, 120, 300];

    public function __construct(private int $tentativeId)
    {
    }

    public function handle(CorrectionIAService $correction, TentativeCorrectionService $finalisation): void
    {
        $tentative = TentativeEvaluation::with('reponses.question', 'evaluation.chapitre', 'evaluation.cour.chapitres')
            ->findOrFail($this->tentativeId);

        $contexteCours = $this->contexteCoursPour($tentative->evaluation);

        foreach ($tentative->reponses->where('en_attente_ia', true) as $reponse) {
            $note = $correction->corrigerReponseOuverte($reponse->question, $reponse->reponse ?? '', $contexteCours);

            $reponse->update([
                'note_obtenue' => $note,
                'en_attente_ia' => false,
            ]);
        }

        $finalisation->finaliser($tentative);
    }

    private function contexteCoursPour($evaluation): string
    {
        if ($evaluation->type_evaluation === 'test_chapitre' && $evaluation->chapitre) {
            return strip_tags($evaluation->chapitre->contenu ?? '');
        }

        return $evaluation->cour->chapitres
            ->sortBy('ordre_affichage')
            ->pluck('contenu')
            ->filter()
            ->map(fn ($c) => strip_tags($c))
            ->implode("\n\n");
    }
}
