<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $ids = session('cart', []);
        $products = Event::query()
            ->whereIn('id', $ids)
            ->where('status', 'active')
            ->where('visibility', 'public')
            ->with('category')
            ->get();

        return view('cart.index', [
            'products' => $products,
            'total' => $products->sum(fn (Event $product) => $product->price ?: 12.50),
        ]);
    }

    public function add(Event $event): RedirectResponse
    {
        abort_unless($event->status === 'active' && $event->visibility === 'public', 404);

        $cart = session('cart', []);
        if (!in_array($event->id, $cart, true)) {
            $cart[] = $event->id;
        }

        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', 'Produto adicionado ao carrinho.');
    }

    public function remove(Event $event): RedirectResponse
    {
        session(['cart' => array_values(array_diff(session('cart', []), [$event->id]))]);

        return redirect()->route('cart.index');
    }
}
