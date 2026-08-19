<?php

namespace App\Http\Controllers;

use App\Models\Cour;
use App\Models\Evaluation;
use App\Models\InscriptionCours;
use App\Models\Payement;
use App\Models\TentativeEvaluation;
use FedaPay\FedaPay;
use FedaPay\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Notifications\PaiementApprouveNotification;
use App\Notifications\PaiementRefuseNotification;
use App\Notifications\PaiementAnnuleNotification;

class PaiementController extends Controller
{
    public function __construct()
    {
        FedaPay::setApiKey(config('fedapay.secret_key'));
        FedaPay::setEnvironment(config('fedapay.environment'));
    }

    public function initier(Cour $cour)
    {
        if (! $cour->estPublie()) {
            abort(404);
        }

        $dejaApprouve = Payement::where('user_id', Auth::id())
            ->where('cour_id', $cour->id)
            ->where('statut_paiement', 'approved')
            ->exists();

        if ($dejaApprouve) {
            return redirect()->route('catalogue.show', $cour)->with('success', 'Vous avez déjà accès à ce cours.');
        }

        // Le test du chapitre 1 (introduction gratuite) est désormais facultatif et ne bloque plus
        // l'achat : seuls les chapitres après achat imposent la validation séquentielle obligatoire.

        $reference = 'CL-' . strtoupper(Str::random(10));

        $transaction = Transaction::create([
            'description' => 'Achat du cours : ' . $cour->titre,
            'amount' => (int) $cour->prix,
            'currency' => ['iso' => 'XOF'],
            'callback_url' => route('paiements.retour', $cour),
            'customer' => [
                'firstname' => Auth::user()->prenom,
                'lastname' => Auth::user()->nom,
                'email' => Auth::user()->email,
            ],
        ]);

        $payement = Payement::create([
            'user_id' => Auth::id(),
            'cour_id' => $cour->id,
            'montant' => $cour->prix,
            'reference' => $reference,
            'transaction_id' => $transaction->id,
            'statut_paiement' => 'pending',
        ]);

        $token = $transaction->generateToken();

        return view('paiements.checkout', [
            'cour' => $cour,
            'payement' => $payement,
            'token' => $token->token,
            'publicKey' => config('fedapay.public_key'),
            'environment' => config('fedapay.environment'),
        ]);
    }

    public function retour(Request $request, Cour $cour)
    {
        return redirect()->route('catalogue.show', $cour)
            ->with('success', 'Paiement en cours de traitement. Le statut sera confirmé sous peu.');
    }

    public function webhook(Request $request)
    {
        try {

            Log::info('=== WEBHOOK FEDAPAY ===');
            Log::info($request->all());

            $payload = $request->all();

            $event = $payload['name'] ?? null;
            $entity = $payload['entity'] ?? null;

            if (!$event || !$entity || !isset($entity['id'])) {

                Log::warning('Payload webhook invalide', [
                    'payload' => $payload
                ]);

                return response()->json([
                    'message' => 'Payload invalide'
                ], 400);
            }

            $payement = Payement::where(
                'transaction_id',
                $entity['id']
            )->first();

            if (!$payement) {

                Log::warning('Paiement introuvable', [
                    'transaction_id' => $entity['id']
                ]);

                return response()->json([
                    'message' => 'Paiement introuvable'
                ], 404);
            }

            $statutMap = [
                'transaction.approved'    => 'approved',
                'transaction.declined'    => 'declined',
                'transaction.canceled'    => 'canceled',
                'transaction.transferred' => 'transfered',
                'transaction.refunded'    => 'refunded',
            ];

            if (!isset($statutMap[$event])) {

                Log::info('Evènement ignoré', [
                    'event' => $event
                ]);

                return response()->json([
                    'message' => 'Evènement ignoré'
                ]);
            }

            DB::beginTransaction();

            $nouveauStatut = $statutMap[$event];

            $payement->update([
                'statut_paiement' => $nouveauStatut
            ]);

            switch ($nouveauStatut) {

                case 'approved':

                    InscriptionCours::firstOrCreate([
                        'payement_id' => $payement->id,
                        'statut' => 'en cours',
                        'progression' => 0,
                    ]);

                    $payement->user->notify(
                        new PaiementApprouveNotification($payement)
                    );

                    break;

                case 'declined':

                    $payement->user->notify(
                        new PaiementRefuseNotification($payement)
                    );

                    break;

                case 'canceled':

                    $payement->user->notify(
                        new PaiementAnnuleNotification($payement)
                    );

                    break;
            }

            DB::commit();

            Log::info('Paiement mis à jour', [
                'payement_id' => $payement->id,
                'statut' => $nouveauStatut
            ]);

            return response()->json([
                'message' => 'ok'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('ERREUR WEBHOOK FEDAPAY', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Erreur serveur'
            ], 500);
        }
    }
}