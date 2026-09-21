@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8">
    <p class="wine-kicker mb-2">Histórico</p>
    <h1 class="font-serif text-5xl text-stone-900 mb-10">As minhas compras</h1>

    @if($purchases->isEmpty())
        <div class="bg-white border border-stone-200 p-12 text-center">
            <p class="font-serif text-2xl text-stone-900 mb-3">Ainda não tem compras.</p>
            <a href="{{ route('events.index') }}" class="wine-button inline-block rounded-lg px-6 py-3 font-semibold">Explorar catálogo</a>
        </div>
    @else
        <div class="space-y-10">
            @foreach($purchases as $purchase)
                @php
                    $first = $purchase->first();
                    $orderStatus = $first->order?->status ?? $first->status;
                    $statusLabel = in_array($orderStatus, ['paid', 'confirmed'], true) ? 'Confirmada' : 'Cancelada';
                @endphp
                <a href="{{ route('registrations.show', $first) }}" class="purchase-card block bg-white border border-stone-200 shadow-lg rounded-xl p-6 hover:shadow-xl transition">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                        <div>
                            <p class="wine-kicker mb-1">Data da compra</p>
                            <h2 class="font-serif text-2xl text-stone-900 purchase-accent">{{ $first->order?->created_at?->format('d/m/Y') ?? $first->created_at->format('d/m/Y') }}</h2>
                        </div>
                        <span class="text-sm font-semibold text-[#5b1820] purchase-accent">Ver recibo completo →</span>
                    </div>

                    <div class="space-y-3 border-y border-stone-200 py-4">
                        @foreach($purchase->groupBy('event_id') as $items)
                            @php($product = $items->first()->event)
                            <div class="flex items-center justify-between gap-4 text-sm">
                                <span class="font-semibold text-stone-800">{{ $product->title }}</span>
                                <span class="text-stone-500">{{ $items->count() }} garrafa(s) · {{ number_format($product->sale_price * $items->count(), 2, ',', '.') }} €</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex flex-wrap items-center justify-between gap-4 mt-5">
                        <span class="purchase-status px-3 py-1 rounded-full text-sm {{ $statusLabel === 'Confirmada' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ $statusLabel }}</span>
                        <span class="font-semibold text-stone-700">{{ number_format($purchase->sum(fn ($item) => $item->event->sale_price) + ($first->order?->shipping_cost ?? $first->shipping_cost ?? 0), 2, ',', '.') }} €</span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
