<?php

namespace App\Notifications;

use App\Notifications\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CertificatDelivreNotification extends Notification
{
    use Queueable;

    public function __construct(public string $coursTitre, public string $codeVerification) {}

    public function via($notifiable): array
    {
        return ['mail', SmsChannel::class, 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Félicitations, votre certificat est prêt !')
            ->greeting('Félicitations ' . $notifiable->prenom . ' !')
            ->line('Vous avez réussi l\'examen final du cours "' . $this->coursTitre . '".')
            ->line('Votre certificat est disponible, code de vérification : ' . $this->codeVerification)
            ->action('Télécharger mon certificat', route('certificats.index'));
    }

    public function toSms($notifiable): string
    {
        return "Félicitations ! Votre certificat CodeLearn pour \"{$this->coursTitre}\" est prêt. Code : {$this->codeVerification}";
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'certificat_delivre',
            'titre' => 'Certificat obtenu',
            'message' => 'Votre certificat pour "' . $this->coursTitre . '" est prêt (code : ' . $this->codeVerification . ').',
            'greeting' => 'Félicitations ' . $notifiable->prenom . ' !',
            'details' => [
                'Vous avez réussi l\'examen final du cours "' . $this->coursTitre . '".',
                'Votre certificat est disponible, code de vérification : ' . $this->codeVerification,
            ],
            'url' => route('certificats.index'),
            'bouton' => 'Télécharger mon certificat',
        ];
    }
}