<?php

namespace App\Notifications;

use App\Models\Bondelivraison;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BonDeLivraisonNotification extends Notification
{
    use Queueable;

    protected $bon;

    public function __construct(Bondelivraison $bon)
    {
        $this->bon = $bon;
    }

    // Canaux de notification (database uniquement)
    public function via($notifiable)
    {
        return ['database'];
    }

    // Données stockées en base
    public function toDatabase($notifiable)
    {
        return [
            'bon_id' => $this->bon->id,
            'bondelivraison' => $this->bon->bondelivraison,
            'fournisseur' => $this->bon->fournisseur->nom ?? 'N/A',
            'date_livraison' => $this->bon->date_livraison,
            'message' => "Nouveau bon de livraison reçu : {$this->bon->bondelivraison}",
            'url' => route('bondelivraison.show', [
                'id' => $this->bon->id,
                'notification' => 'true', // Paramètre pour marquer la notif comme lue
            ]),
        ];
    }
}
