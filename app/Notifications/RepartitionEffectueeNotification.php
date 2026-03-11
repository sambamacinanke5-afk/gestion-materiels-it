<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RepartitionEffectueeNotification extends Notification
{
    use Queueable;

    protected $repartition;

    public function __construct($repartition)
    {
        $this->repartition = $repartition;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'repartition_id' => $this->repartition->id,
            'reference'      => $this->repartition->repartition,
            'message' => "Nouvelle répartition reçue : " . optional($this->repartition->bondelivraison)->bondelivraison,
            'url'            => route('repartition.show', [
                'id' => $this->repartition->id,
                'notification' => true
            ]),
        ];
    }
}
