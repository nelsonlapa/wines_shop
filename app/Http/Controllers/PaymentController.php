<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketPurchased;

class PaymentController extends Controller
{
    public function checkout(Request $request, $id = null)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $eventId = $request->input('event_id') ?? $id ?? 1;
        $event = \App\Models\Event::findOrFail($eventId);
        
        $userId = Auth::id() ?? 1; 

        // 🛑 O NOSSO NOVO SEGURANÇA: Verifica se já existe inscrição antes de ir para a Stripe
        $jaTemBilhete = Registration::where('user_id', $userId)
                                    ->where('event_id', $eventId)
                                    ->exists();

        if ($jaTemBilhete) {
            // Devolve o utilizador para a página onde estava com uma mensagem de erro
            return redirect()->back()->with('error', 'Já possuis um bilhete para este evento. Não podes comprar em duplicado.');
        }
        
        $ticketId = $request->input('ticket_id');
        
        $finalPrice = $event->sale_price;
        $ticketName = 'Geral';

        if ($ticketId) {
            $ticket = $event->ticketTypes()->find($ticketId);
            if ($ticket) {
                $finalPrice = $ticket->price; 
                $ticketName = $ticket->name;  
            }
        }

        // 1. GERAMOS O TOKEN DO BILHETE AQUI!
        $ticketToken = strtoupper(Str::random(12));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $event->title . ' - ' . $ticketName, 
                    ],
                    'unit_amount' => $finalPrice * 100, 
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/pagamento-sucesso?session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url' => url('/pagamento-cancelado'),
            'metadata' => [
                'event_id' => $event->id,
                'user_id' => $userId,
                'ticket_id' => $ticketId,
                'ticket_token' => $ticketToken, // 2. ENVIAMOS O TOKEN PARA A STRIPE
            ],
        ]);

        return redirect()->away($session->url);
    }

    public function success(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        
        try {
            $session = Session::retrieve($request->get('session_id'));
            
            // 3. VAMOS BUSCAR O TOKEN QUE ESTAVA NA MALA DA STRIPE
            $ticketToken = $session->metadata->ticket_token;

            // 4. VERIFICA SE O WEBHOOK JÁ SE ANTECIPOU E CRIOU O BILHETE
            $registration = Registration::where('ticket_token', $ticketToken)->first();

            // 5. SE AINDA NÃO EXISTIR, CRIAMOS NÓS AQUI
            if (!$registration) {
                $registration = Registration::create([
                    'user_id' => $session->metadata->user_id,
                    'event_id' => $session->metadata->event_id,
                    'status' => 'confirmed',
                    'ticket_token' => $ticketToken,
                    'checked_in' => false,
                ]);

                // ENVIA O EMAIL APENAS SE FOI CRIADO AGORA
                Mail::to($registration->user->email)->send(new TicketPurchased($registration));
            }

            // Mostra a vista (quer tenha sido criado agora ou pelo webhook)
            return view('payment.success', compact('registration'));

        } catch (\Exception $e) {
            return "Erro ao processar o sucesso: " . $e->getMessage();
        }
    }

    public function cancel()
    {
        return "O pagamento foi cancelado.";
    }
}