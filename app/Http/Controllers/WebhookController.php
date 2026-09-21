<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use App\Models\Registration;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketPurchased;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

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
            
            if (isset($session->metadata->purchase_items)) {
                $user = User::find($session->metadata->user_id);
                $purchaseItems = json_decode($session->metadata->purchase_items, true) ?? [];
                $checkoutDetails = json_decode($session->metadata->checkout_details ?? '{}', true) ?? [];
                $order = Order::firstOrCreate(
                    ['order_reference' => $checkoutDetails['order_reference'] ?? 'AN-' . $session->id],
                    array_merge($checkoutDetails, [
                        'number' => $checkoutDetails['number'] ?? $checkoutDetails['order_reference'],
                        'user_id' => $session->metadata->user_id,
                        'status' => 'paid',
                        'stripe_session_id' => $session->id,
                    ]),
                );

                if ($user) {
                    $newItems = collect($purchaseItems)
                        ->reject(fn (array $item) => Registration::where('ticket_token', $item['ticket_token'])->exists())
                        ->values();

                    DB::transaction(function () use ($newItems, $order, $session, $checkoutDetails, &$registration): void {
                        foreach ($newItems->groupBy('event_id') as $eventId => $items) {
                            $eventProduct = Event::query()->lockForUpdate()->findOrFail($eventId);
                            $sold = $eventProduct->registrations()->where('status', 'confirmed')->count();

                            if ($sold + $items->count() > $eventProduct->capacity) {
                                $order->update(['status' => 'cancelled']);
                                throw new \RuntimeException('Stock insuficiente para ' . $eventProduct->title . '.');
                            }
                        }

                        foreach ($newItems as $item) {
                            $registration = Registration::create([
                                'user_id' => $session->metadata->user_id,
                                'order_id' => $order->id,
                                'event_id' => $item['event_id'],
                                'status' => 'confirmed',
                                'ticket_token' => $item['ticket_token'],
                                'checked_in' => false,
                                ...$checkoutDetails,
                                'stripe_session_id' => $session->id,
                            ]);
                            Log::info('Stripe Webhook: Venda #' . $registration->id . ' confirmada.');
                        }
                    });

                    if (isset($registration)) {
                        Mail::to($user->email)->send(new TicketPurchased($registration->load('event', 'user')));
                    }
                }
            }
        }

        return response('Webhook recebido com sucesso', 200);
    }
}