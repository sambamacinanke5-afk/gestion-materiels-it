<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

namespace App\Mail;

use App\Models\Bondelivraison;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BonLivraisonCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Bondelivraison $bondelivraison;

    public function __construct(Bondelivraison $bondelivraison)
    {
        $this->bondelivraison = $bondelivraison;
    }

    public function build()
    {
        return $this->subject('🔴 Nouveau Bon de Livraison')
                    ->view('emails.bondelivraison.create')
                    ->with([
                        'bon' => $this->bondelivraison,
                    ]);
    }
}
