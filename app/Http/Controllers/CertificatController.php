<?php

namespace App\Http\Controllers;

use App\Models\Certificat;
use App\Models\Evaluation;
use App\Models\InscriptionCours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificatController extends Controller
{
    /**
     * Génère le certificat après réussite de l'examen final (RG05).
     * RG10 : un seul certificat par cours -> firstOrCreate sur inscriptions_cour_id.
     */
    public function genererPour(InscriptionCours $inscription, Evaluation $evaluation)
    {
        if ($inscription->certificat) {
            return $inscription->certificat; // déjà généré (RG10)
        }

        $code = $this->genererCodeUnique();

        $certificat = Certificat::create([
            'inscriptions_cour_id' => $inscription->id,
            'score_final' => $evaluation->cour->chapitres()->count() > 0
                ? \App\Models\TentativeEvaluation::where('user_id', $inscription->apprenant()?->id)
                    ->where('evaluation_id', $evaluation->id)->latest()->value('score') ?? 0
                : 0,
            'fichier_pdf' => '',
            'code_verification' => $code,
        ]);

        // Génération du PDF - nécessite le package barryvdh/laravel-dompdf (voir composer.json).
        // Ce package n'a pas pu être installé dans mon bac à sable (Packagist bloqué) ; le code
        // ci-dessous est donc écrit pour fonctionner chez toi une fois `composer install` lancé,
        // avec un repli silencieux si le package est absent pour ne pas casser le flux.
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            try {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('certificats.pdf', [
                    'certificat' => $certificat,
                    'inscription' => $inscription,
                    'apprenant' => $inscription->apprenant(),
                    'cour' => $evaluation->cour,
                ]);

                $chemin = 'certificats/' . $code . '.pdf';
                Storage::disk('public')->put($chemin, $pdf->output());
                $certificat->update(['fichier_pdf' => $chemin]);
            } catch (\Throwable $e) {
                Log::warning('Génération PDF du certificat échouée : ' . $e->getMessage());
            }
        } else {
            Log::info('Package barryvdh/laravel-dompdf absent : certificat créé sans fichier PDF (à générer après composer install).');
        }

        return $certificat;
    }

    private function genererCodeUnique(): string
    {
        do {
            $code = 'CL-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
        } while (Certificat::where('code_verification', $code)->exists());

        return $code;
    }

    public function mesCertificats()
    {
        $certificats = Certificat::whereHas('inscription.payement', function ($q) {
            $q->where('user_id', Auth::id());
        })->with('inscription.payement.cour')->get();

        return view('certificats.index', compact('certificats'));
    }

    public function telecharger(Certificat $certificat)
    {
        if ($certificat->inscription->payement->user_id !== Auth::id() && ! Auth::user()->estAdministrateur()) {
            abort(403);
        }

        if (! $certificat->fichier_pdf || ! Storage::disk('public')->exists($certificat->fichier_pdf)) {
            abort(404, "Le fichier PDF n'est pas encore disponible.");
        }

        return Storage::disk('public')->download($certificat->fichier_pdf);
    }

    public function formulaireVerification()
    {
        return view('certificats.verifier');
    }

    public function verifier(Request $request)
    {
        $code = $request->input('code');

        $certificat = Certificat::with('inscription.payement.user', 'inscription.payement.cour')
            ->where('code_verification', $code)->first();

        return view('certificats.verifier', compact('certificat', 'code'));
    }
}
