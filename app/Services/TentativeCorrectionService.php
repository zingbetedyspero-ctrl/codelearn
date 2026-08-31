<?php

namespace App\Services;

use App\Http\Controllers\CertificatController;
use App\Models\Evaluation;
use App\Models\InscriptionCours;
use App\Models\TentativeEvaluation;
use App\Notifications\CertificatDelivreNotification;
use App\Notifications\ChapitreDebloqueNotification;

class TentativeCorrectionService
{
    public function finaliser(TentativeEvaluation $tentative): void
    {
        $tentative->load('reponses.question', 'evaluation.cour.chapitres', 'evaluation.chapitre');

        if ($tentative->reponses->contains('en_attente_ia', true)) {
            return; // il reste des réponses non corrigées, on attend
        }

        $totalBareme = $tentative->reponses->sum(fn ($r) => $r->question->bareme);
        $totalObtenu = $tentative->reponses->sum('note_obtenue');
        $scorePourcent = $totalBareme > 0 ? round(($totalObtenu / $totalBareme) * 100, 2) : 0;
        $reussi = $scorePourcent >= $tentative->evaluation->seuil_reussite;

        $tentative->update([
            'score' => $scorePourcent,
            'statut' => $reussi ? 'reussi' : 'echoue',
        ]);

        if ($reussi) {
            $this->traiterReussite($tentative->evaluation, $tentative->user_id);
        }
    }

    private function traiterReussite(Evaluation $evaluation, int $userId): void
    {
        $cour = $evaluation->cour;
        $inscription = $this->inscriptionPour($cour, $userId);

        if (! $inscription) {
            return;
        }

        if ($evaluation->type_evaluation === 'test_chapitre') {
            $chapitre = $evaluation->chapitre;

            if ($chapitre && $chapitre->ordre_affichage === $inscription->chapitreDebloqueJusqua()) {
                $inscription->increment('progression');

                $chapitreSuivant = $cour->chapitres()->where('ordre_affichage', $inscription->progression)->first();

                if ($chapitreSuivant) {
                    $inscription->apprenant()->notify(new ChapitreDebloqueNotification($cour, $chapitreSuivant));
                }
            }

            return;
        }

        $inscription->update(['statut' => 'termine']);
        $certificat = app(CertificatController::class)->genererPour($inscription, $evaluation);
        $inscription->apprenant()->notify(new CertificatDelivreNotification($cour->titre, $certificat->code_verification));
    }

    private function inscriptionPour($cour, int $userId): ?InscriptionCours
    {
        return InscriptionCours::whereHas('payement', function ($q) use ($cour, $userId) {
            $q->where('user_id', $userId)->where('cour_id', $cour->id)->where('statut_paiement', 'approved');
        })->first();
    }
}
