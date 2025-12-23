<?php

namespace App\Mail;

use App\Models\TravelRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TravelRequestCreated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public TravelRequest $travelRequest
    ) {
        // Carrega o usuário para garantir acesso ao nome na View
        $this->travelRequest->load('user');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Confirmação: Solicitação de viagem para {$this->travelRequest->destination} criada",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.travel-request-created',
        );
    }
}
