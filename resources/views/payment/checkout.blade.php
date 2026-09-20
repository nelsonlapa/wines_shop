@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8">
    <div class="mb-10">
        <p class="wine-kicker mb-2">Último passo</p>
        <h1 class="font-serif text-5xl text-stone-900">Finalizar compra</h1>
        <p class="text-stone-500 mt-3">Confirma os teus dados e escolhe como queres receber a encomenda.</p>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-8 items-start" x-data="{ delivery: @js(old('delivery_method', 'delivery')), subtotal: {{ $subtotal }}, shipping() { return this.delivery === 'pickup' || this.subtotal >= 75 ? 0 : 5; }, money(value) { return value.toFixed(2).replace('.', ',') + ' €'; } }">
        @csrf
        <div class="space-y-6">
            @if($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-5 py-4 text-red-800">
                    <p class="font-semibold mb-1">Verifica os dados da encomenda.</p>
                    <ul class="text-sm list-disc list-inside">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <section class="bg-white border border-stone-200 p-6">
                <p class="wine-kicker mb-2">Dados do cliente</p>
                <h2 class="font-serif text-2xl text-stone-900 mb-6">Para onde enviamos a encomenda?</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                        <label for="customer_name" class="block text-sm font-semibold text-stone-700 mb-2">Nome</label>
                        <input id="customer_name" name="customer_name" value="{{ old('customer_name', auth()->user()->name) }}" required class="w-full rounded-lg px-4 py-3" autocomplete="name">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="address" class="block text-sm font-semibold text-stone-700 mb-2">Morada</label>
                        <input id="address" name="address" value="{{ old('address') }}" class="w-full rounded-lg px-4 py-3" autocomplete="street-address">
                    </div>
                    <div>
                        <label for="postal_code" class="block text-sm font-semibold text-stone-700 mb-2">Código postal</label>
                        <input id="postal_code" name="postal_code" value="{{ old('postal_code') }}" class="w-full rounded-lg px-4 py-3" autocomplete="postal-code">
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-semibold text-stone-700 mb-2">Cidade</label>
                        <input id="city" name="city" value="{{ old('city') }}" class="w-full rounded-lg px-4 py-3" autocomplete="address-level2">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="phone" class="block text-sm font-semibold text-stone-700 mb-2">Telefone</label>
                        <input id="phone" name="phone" value="{{ old('phone') }}" required class="w-full rounded-lg px-4 py-3" type="tel" autocomplete="tel">
                    </div>
                </div>
            </section>

            <section class="bg-white border border-stone-200 p-6">
                <p class="wine-kicker mb-2">Entrega</p>
                <h2 class="font-serif text-2xl text-stone-900 mb-5">Como queres receber?</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="cursor-pointer border border-stone-300 rounded-lg p-4 has-[:checked]:border-[#5b1820] has-[:checked]:ring-2 has-[:checked]:ring-[#5b1820]/20">
                        <input type="radio" name="delivery_method" value="delivery" x-model="delivery" @checked(old('delivery_method', 'delivery') === 'delivery') class="text-[#5b1820]">
                        <span class="ml-2 font-semibold text-stone-800">Entrega ao domicílio</span>
                        <span class="block text-sm text-stone-500 mt-2">5,00 € · gratuita a partir de 75,00 €</span>
                    </label>
                    <label class="cursor-pointer border border-stone-300 rounded-lg p-4 has-[:checked]:border-[#5b1820] has-[:checked]:ring-2 has-[:checked]:ring-[#5b1820]/20">
                        <input type="radio" name="delivery_method" value="pickup" x-model="delivery" @checked(old('delivery_method') === 'pickup') class="text-[#5b1820]">
                        <span class="ml-2 font-semibold text-stone-800">Levantamento</span>
                        <span class="block text-sm text-stone-500 mt-2">Gratuito · Aroma Nobre</span>
                    </label>
                </div>
            </section>
        </div>

        <aside class="bg-white border border-stone-200 p-6 lg:sticky lg:top-8">
            <p class="wine-kicker mb-2">Resumo final</p>
            <h2 class="font-serif text-2xl text-stone-900 mb-6">A tua encomenda</h2>
            <div class="space-y-4 border-b border-stone-200 pb-5 mb-5">
                @foreach($products as $product)
                    <div class="flex justify-between gap-4 text-sm">
                        <span class="text-stone-600">{{ $product->title }} <strong class="text-stone-900">× {{ $quantities[$product->id] }}</strong></span>
                        <span class="font-semibold whitespace-nowrap">{{ number_format($product->sale_price * $quantities[$product->id], 2, ',', '.') }} €</span>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between text-stone-600 mb-3"><span>Produtos</span><span>{{ number_format($subtotal, 2, ',', '.') }} €</span></div>
            <div class="flex justify-between text-stone-600 mb-5"><span>Envio</span><span x-text="shipping() ? money(shipping()) : 'Grátis'"></span></div>
            <div class="flex justify-between border-t border-stone-200 pt-5 mb-6 text-lg font-bold text-stone-900"><span>Total</span><span x-text="money(subtotal + shipping())"></span></div>
            <button type="submit" class="wine-button w-full rounded-lg py-4 font-semibold">Continuar para pagamento</button>
            <p class="text-xs text-stone-500 text-center mt-3">Pagamento seguro processado pela Stripe.</p>
        </aside>
    </form>
</div>
@endsection
