<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Payement;

class PaiementAnnuleNotification extends Notification
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
            ->subject('Paiement annulé')
            ->greeting('Bonjour '.$notifiable->prenom)
            ->line('Votre paiement a été annulé.')
            ->line('Aucun montant n’a été débité.')
            ->action(
                'Retour au cours',
                route('catalogue.show', $this->payement->cour)
            );
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'paiement_annule',
            'titre' => 'Paiement annulé',
            'message' => 'La transaction a été annulée.',
            'greeting' => 'Bonjour '.$notifiable->prenom,
            'details' => [
                'Votre paiement a été annulé.',
                'Aucun montant n’a été débité.',
            ],
            'cour_id' => $this->payement->cour_id,
            'url' => route('catalogue.show', $this->payement->cour),
            'bouton' => 'Retour au cours',
        ];
    }
}