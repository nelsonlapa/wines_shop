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
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function checkout(): View
    {
        [$products, $quantities, $subtotal] = $this->cartSummary();
        abort_if($products->isEmpty(), 400, 'O carrinho está vazio.');

        return view('payment.checkout', [
            'products' => $products,
            'quantities' => $quantities,
            'subtotal' => $subtotal,
            'shippingCost' => $this->shippingCost($subtotal, 'delivery'),
        ]);
    }

    public function processCheckout(Request $request): \Illuminate\Http\RedirectResponse
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $userId = Auth::id();
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'address' => ['required_if:delivery_method,delivery', 'nullable', 'string', 'max:255'],
            'postal_code' => ['required_if:delivery_method,delivery', 'nullable', 'string', 'max:30'],
            'city' => ['required_if:delivery_method,delivery', 'nullable', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:40'],
            'delivery_method' => ['required', 'in:delivery,pickup'],
        ]);

        [$products, $quantities, $subtotal] = $this->cartSummary();
        abort_if($products->isEmpty(), 400, 'O carrinho está vazio.');
        $shippingCost = $this->shippingCost($subtotal, $validated['delivery_method']);
        $validated['shipping_cost'] = $shippingCost;
        if ($validated['delivery_method'] === 'pickup') {
            $validated['address'] = null;
            $validated['postal_code'] = null;
            $validated['city'] = null;
        }

        $purchaseItems = [];
        $lineItems = [];

        foreach ($products as $event) {
            $quantity = $quantities[$event->id];

            for ($unit = 0; $unit < $quantity; $unit++) {
                $purchaseItems[] = [
                    'event_id' => $event->id,
                    'ticket_token' => strtoupper(Str::random(12)),
                ];
            }

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => ['name' => $event->title],
                    'unit_amount' => (int) round($event->sale_price * 100),
                ],
                'quantity' => $quantity,
            ];
        }

        if ($shippingCost > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => ['name' => 'Envio Aroma Nobre'],
                    'unit_amount' => (int) round($shippingCost * 100),
                ],
                'quantity' => 1,
            ];
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => url('/pagamento-sucesso?session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url' => url('/pagamento-cancelado'),
            'metadata' => [
                'user_id' => $userId,
                'purchase_items' => json_encode($purchaseItems),
                'checkout_details' => json_encode($validated),
            ],
        ]);

        return redirect()->away($session->url);
    }

    private function cartSummary(): array
    {
        $cart = session('cart', []);
        if (array_is_list($cart)) {
            $cart = array_count_values($cart);
        }

        $quantities = collect($cart)
            ->mapWithKeys(fn ($quantity, $eventId): array => [(int) $eventId => max(1, (int) $quantity)])
            ->all();

        $products = \App\Models\Event::query()
            ->whereIn('id', array_keys($quantities))
            ->where('status', 'active')
            ->where('visibility', 'public')
            ->with('category')
            ->get()
            ->keyBy('id');

        $subtotal = $products->sum(fn ($product) => $product->sale_price * $quantities[$product->id]);

        return [$products->values(), $quantities, $subtotal];
    }

    private function shippingCost(float $subtotal, string $deliveryMethod): float
    {
        if ($deliveryMethod === 'pickup' || $subtotal >= 75) {
            return 0;
        }

        return 5;
    }

    public function success(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        
        try {
            $session = Session::retrieve($request->get('session_id'));
            
            $purchaseItems = json_decode($session->metadata->purchase_items ?? '[]', true);
            $checkoutDetails = json_decode($session->metadata->checkout_details ?? '{}', true);
            $registrations = collect();
            $createdRegistration = false;

            foreach ($purchaseItems as $item) {
                $registration = Registration::firstOrCreate(
                    ['ticket_token' => $item['ticket_token']],
                    [
                        'user_id' => $session->metadata->user_id,
                        'event_id' => $item['event_id'],
                        'status' => 'confirmed',
                        'checked_in' => false,
                        ...$checkoutDetails,
                        'stripe_session_id' => $session->id,
                    ],
                );
                $createdRegistration = $createdRegistration || $registration->wasRecentlyCreated;
                $registrations->push($registration->load('event', 'user'));
            }

            if ($registrations->isEmpty()) {
                throw new \RuntimeException('Não foi possível identificar os produtos desta compra.');
            }

            if ($createdRegistration) {
                Mail::to($registrations->first()->user->email)->send(new TicketPurchased($registrations->first()));
            }

            session()->forget('cart');

            return view('payment.success', compact('registrations'));

        } catch (\Exception $e) {
            return "Erro ao processar o sucesso: " . $e->getMessage();
        }
    }

    public function cancel()
    {
        return "O pagamento foi cancelado.";
    }
}