<?php

namespace App\Notifications;

use App\Models\Chapitre;
use App\Models\Cour;
use App\Notifications\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChapitreDebloqueNotification extends Notification
{
    use Queueable;

    public function __construct(public Cour $cour, public Chapitre $chapitre) {}

    public function via($notifiable): array
    {
        return ['mail', SmsChannel::class, 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouveau chapitre débloqué - ' . $this->cour->titre)
            ->greeting('Bravo ' . $notifiable->prenom . ' !')
            ->line('Vous avez validé le chapitre précédent avec succès.')
            ->line('Le chapitre "' . $this->chapitre->titre . '" est maintenant débloqué.')
            ->action('Continuer le cours', route('catalogue.chapitre', [$this->cour, $this->chapitre]));
    }

    public function toSms($notifiable): string
    {
        return "CodeLearn : chapitre \"{$this->chapitre->titre}\" débloqué dans \"{$this->cour->titre}\". Continuez !";
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'chapitre_debloque',
            'titre' => 'Chapitre débloqué',
            'message' => 'Le chapitre "' . $this->chapitre->titre . '" est maintenant accessible.',
            'greeting' => 'Bravo ' . $notifiable->prenom . ' !',
            'details' => [
                'Vous avez validé le chapitre précédent avec succès.',
                'Le chapitre "' . $this->chapitre->titre . '" est maintenant débloqué.',
            ],
            'cour_id' => $this->cour->id,
            'url' => route('catalogue.chapitre', [$this->cour, $this->chapitre]),
            'bouton' => 'Continuer le cours',
        ];
    }
}