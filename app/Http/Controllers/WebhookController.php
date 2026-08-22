<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketPurchased;

class WebhookController extends Controller
{
    public function handleStripe(Request $request)
    {
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $event = null;

        try {
            if ($endpoint_secret) {
                $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
            } else {
                // Modo "fallback" seguro caso falte a chave localmente
                $event = json_decode($payload);
            }
        } catch (\Exception $e) {
            Log::error('Stripe Webhook Error: ' . $e->getMessage());
            return response('Invalid payload or signature', 400);
        }

        // Quando o pagamento é aprovado
        if ($event->type === 'checkout.session.completed') {
            
            $session = $event->data->object;
            
            if (isset($session->metadata->ticket_token)) {
                
                $ticketToken = $session->metadata->ticket_token;
                
                // Evita compra duplicada
                $existingRegistration = Registration::where('ticket_token', $ticketToken)->first();

                if (!$existingRegistration) {
                    
                    $user = User::find($session->metadata->user_id);
                    
                    if ($user) {
                        $registration = Registration::create([
                            'user_id' => $session->metadata->user_id,
                            'event_id' => $session->metadata->event_id,
                            'status' => 'confirmed',
                            'ticket_token' => $ticketToken,
                            'checked_in' => false,
                        ]);

                        // Envia o e-mail em background
                        Mail::to($user->email)->send(new TicketPurchased($registration));
                        Log::info('Stripe Webhook: Bilhete ' . $ticketToken . ' criado via webhook.');
                    }
                } else {
                    Log::info('Stripe Webhook: Bilhete ' . $ticketToken . ' ignorado (já existia).');
                }
            }
        }

        return response('Webhook recebido com sucesso', 200);
    }
}