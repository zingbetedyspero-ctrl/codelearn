<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Payement;

class PaiementRefuseNotification extends Notification
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
            ->subject('Paiement refusé')
            ->greeting('Bonjour '.$notifiable->prenom)
            ->line('Votre paiement pour le cours "'.$this->payement->cour->titre.'" a été refusé.')
            ->line('Vous pouvez réessayer à tout moment.')
            ->action(
                'Réessayer le paiement',
                route('catalogue.show', $this->payement->cour)
            );
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'paiement_refuse',
            'titre' => 'Paiement refusé',
            'message' => 'Le paiement du cours a échoué.',
            'greeting' => 'Bonjour '.$notifiable->prenom,
            'details' => [
                'Votre paiement pour le cours "'.$this->payement->cour->titre.'" a été refusé.',
                'Vous pouvez réessayer à tout moment.',
            ],
            'cour_id' => $this->payement->cour_id,
            'url' => route('catalogue.show', $this->payement->cour),
            'bouton' => 'Réessayer le paiement',
        ];
    }
}