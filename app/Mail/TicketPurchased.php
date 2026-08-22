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

    // Recebemos a inscrição quando chamamos o email
    public function __construct(Registration $registration)
    {
        $this->registration = $registration;
    }

    // O Assunto do e-mail
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'O teu bilhete para ' . $this->registration->event->title,
        );
    }

    // O Ficheiro visual do e-mail
    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket_purchased',
        );
    }
}