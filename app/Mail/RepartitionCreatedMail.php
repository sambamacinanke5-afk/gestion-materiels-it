<?php

namespace App\Mail;

use App\Models\Repartition;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RepartitionCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Repartition $repartition;

    /**
     * Création du mail
     */
    public function __construct(Repartition $repartition)
    {
        // Charger les relations nécessaires UNE FOIS
        $this->repartition = $repartition->load([
            'bondelivraison',
            'lignes.service',
        ]);
    }

    /**
     * Construction du mail
     */
    public function build()
    {
        return $this->subject('🔴 Nouvelle répartition de matériel')
            ->view('emails.repartition.create')
            ->with([
                'repartition' => $this->repartition,
            ]);
    }
}
