<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = $this->cartContents();
        $products = Event::query()
            ->whereIn('id', array_keys($cart))
            ->where('status', 'active')
            ->where('visibility', 'public')
            ->with('category')
            ->get();

        return view('cart.index', [
            'products' => $products,
            'quantities' => $cart,
            'total' => $products->sum(fn (Event $product) => $product->sale_price * $cart[$product->id]),
        ]);
    }

    public function add(Event $event): RedirectResponse
    {
        abort_unless($event->status === 'active' && $event->visibility === 'public', 404);

        if ($event->available_stock < 1) {
            return redirect()->route('cart.index')->with('error', 'Este produto está esgotado.');
        }

        $cart = $this->cartContents();
        $cart[$event->id] = min(($cart[$event->id] ?? 0) + 1, $event->available_stock);

        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', 'Produto adicionado ao carrinho.');
    }

    public function remove(Event $event): RedirectResponse
    {
        $cart = $this->cartContents();
        unset($cart[$event->id]);
        session(['cart' => $cart]);

        return redirect()->route('cart.index');
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $quantity = $request->integer('quantity');
        $cart = $this->cartContents();

        if ($quantity < 1) {
            unset($cart[$event->id]);
        } else {
            $cart[$event->id] = min($quantity, $event->available_stock);
        }

        session(['cart' => $cart]);

        return redirect()->route('cart.index');
    }

    private function cartContents(): array
    {
        $cart = session('cart', []);

        if (array_is_list($cart)) {
            $cart = array_count_values($cart);
        }

        return collect($cart)
            ->mapWithKeys(fn ($quantity, $id): array => [(int) $id => max(1, (int) $quantity)])
            ->all();
    }
}
