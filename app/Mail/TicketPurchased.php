<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Registration;

class TicketPurchased extends Mailable
{
    use Queueable, SerializesModels;

    public $registration;

    public function __construct(Registration $registration)
    {
        $this->registration = $registration;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Compra confirmada | Aroma Nobre',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket_purchased',
        );
    }
}