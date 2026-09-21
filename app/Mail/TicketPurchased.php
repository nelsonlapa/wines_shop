<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Registration;
use Illuminate\Support\Collection;

class TicketPurchased extends Mailable
{
    use Queueable, SerializesModels;

    public $registration;
    public Collection $registrations;

    public function __construct(Registration $registration)
    {
        $this->registration = $registration->load('event', 'user', 'order');
        $this->registrations = $registration->order_id
            ? Registration::where('order_id', $registration->order_id)
                ->with('event')
                ->get()
            : collect([$this->registration]);
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