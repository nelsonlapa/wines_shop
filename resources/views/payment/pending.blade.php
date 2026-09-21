@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-16 text-center">
    <div class="bg-white border border-stone-200 p-8 rounded-xl">
        <p class="wine-kicker mb-3">Pagamento recebido</p>
        <h1 class="font-serif text-4xl text-stone-900 mb-4">Estamos a confirmar a tua compra</h1>
        <p class="text-stone-500 mb-6">A confirmação final está a ser processada. Receberás o email da Aroma Nobre assim que estiver concluída.</p>
        <p class="text-sm text-stone-500 mb-8">Referência: <strong class="text-stone-800">{{ $reference }}</strong></p>
        <a href="{{ route('registrations.my') }}" class="wine-button inline-block rounded-lg px-6 py-3 font-semibold">Ver as minhas compras</a>
    </div>
</div>
@endsection
