<?php

namespace App\Mail;

use App\Models\TravelRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TravelRequestStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public TravelRequest $travelRequest
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Atualização no seu pedido de viagem para {$this->travelRequest->destination}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.travel-status-updated',
        );
    }
}
