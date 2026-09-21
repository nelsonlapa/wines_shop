<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Registration;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketPurchased;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function checkout(): View
    {
        [$products, $quantities, $subtotal] = $this->cartSummary();
        abort_if($products->isEmpty(), 400, 'O carrinho está vazio.');

        foreach ($products as $product) {
            if ($quantities[$product->id] > $product->available_stock) {
                return redirect()->route('cart.index')->with('error', "Stock insuficiente para {$product->title}.");
            }
        }

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
            'country_code' => ['required', 'regex:/^\+?\d{1,4}$/'],
            'phone' => ['required', 'string', 'max:40'],
            'delivery_method' => ['required', 'in:delivery,pickup'],
        ]);

        [$products, $quantities, $subtotal] = $this->cartSummary();
        abort_if($products->isEmpty(), 400, 'O carrinho está vazio.');
        foreach ($products as $product) {
            if ($quantities[$product->id] > $product->available_stock) {
                return redirect()->route('cart.index')->with('error', "Stock insuficiente para {$product->title}.");
            }
        }
        $shippingCost = $this->shippingCost($subtotal, $validated['delivery_method']);
        $validated['order_reference'] = 'AN-' . strtoupper(Str::random(8));
        $countryCode = '+' . ltrim($validated['country_code'], '+');
        $validated['phone'] = $countryCode . ' ' . preg_replace('/\s+/', ' ', trim($validated['phone']));
        unset($validated['country_code']);
        $validated['shipping_cost'] = $shippingCost;
        $validated['subtotal'] = $subtotal;
        $validated['total'] = $subtotal + $shippingCost;
        $validated['number'] = $validated['order_reference'];
        $validated['shipping_name'] = $validated['customer_name'];
        $validated['shipping_address'] = $validated['address'] ?? '';
        $validated['shipping_city'] = $validated['city'] ?? '';
        $validated['shipping_postcode'] = $validated['postal_code'] ?? '';
        $validated['shipping_country'] = 'PT';
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
        if ($deliveryMethod === 'pickup') {
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
            $order = Order::where('stripe_session_id', $session->id)->first();
            $registrations = Registration::where('stripe_session_id', $session->id)->with('event', 'user', 'order')->get();

            if ($registrations->isEmpty()) {
                return view('payment.pending', ['reference' => $checkoutDetails['order_reference'] ?? 'A confirmar']);
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