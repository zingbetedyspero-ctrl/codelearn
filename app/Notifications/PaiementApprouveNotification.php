<?php

namespace App\Notifications;

use App\Notifications\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Payement;

class PaiementApprouveNotification extends Notification
{
    public function __construct(
        public Payement $payement
    ) {}

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Paiement confirmé')
            ->greeting('Bonjour '.$notifiable->prenom)
            ->line('Votre paiement a été validé avec succès.')
            ->line('Vous avez maintenant accès au cours : '.$this->payement->cour->titre)
            ->action(
                'Accéder au cours',
                route('catalogue.show', $this->payement->cour)
            )
            ->line('Merci pour votre confiance.');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'paiement_approuve',
            'titre' => 'Paiement confirmé',
            'message' => 'Votre accès au cours a été activé.',
            'greeting' => 'Bonjour '.$notifiable->prenom,
            'details' => [
                'Votre paiement a été validé avec succès.',
                'Vous avez maintenant accès au cours : '.$this->payement->cour->titre,
                'Merci pour votre confiance.',
            ],
            'cour_id' => $this->payement->cour_id,
            'url' => route('catalogue.show', $this->payement->cour),
            'bouton' => 'Accéder au cours',
        ];
    }
}