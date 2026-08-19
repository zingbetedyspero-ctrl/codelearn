<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Canal SMS générique. Aucun fournisseur SMS spécifique n'a été choisi/fourni,
 * donc ce canal journalise le message par défaut (comme le driver 'log' de Mail),
 * et appelle une API HTTP générique SI la variable SMS_GATEWAY_URL est configurée.
 * À remplacer par l'intégration de ton fournisseur SMS réel (ex: un agrégateur
 * local béninois) en adaptant la méthode send() ci-dessous.
 */
class SmsChannel
{
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSms($notifiable);
        $telephone = $notifiable->telephone ?? null;

        if (! $telephone || ! $message) {
            return;
        }

        $gatewayUrl = config('services.sms.gateway_url');

        if (! $gatewayUrl) {
            Log::info("SMS (non envoyé - aucune passerelle configurée) à {$telephone} : {$message}");
            return;
        }

        try {
            Http::post($gatewayUrl, [
                'to' => $telephone,
                'message' => $message,
                'api_key' => config('services.sms.api_key'),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Envoi SMS échoué : ' . $e->getMessage());
        }
    }
}
