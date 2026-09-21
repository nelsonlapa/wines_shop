<div style="font-family: sans-serif; max-width: 560px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
    
    <h2 style="color: #5b1820;">Olá, {{ $registration->user->name }}!</h2>
    
    <p>A tua compra foi confirmada com sucesso. Obrigado por escolheres a Aroma Nobre.</p>
    
    <div style="background-color: #f3f4f6; padding: 15px; border-radius: 5px; margin: 20px 0;">
        <h3 style="margin-top: 0;">Recibo de compra</h3>
        <p><strong>Referência da compra:</strong> {{ $registration->order?->order_reference ?? $registration->order_reference ?? 'AN-' . $registration->id }}</p>
        <p><strong>Data:</strong> {{ $registration->order?->created_at?->format('d/m/Y H:i') ?? $registration->created_at->format('d/m/Y H:i') }}</p>
        @foreach($registrations->groupBy('event_id') as $items)
            @php($product = $items->first()->event)
            <p style="margin-bottom: 6px;"><strong>Produto:</strong> {{ $product->title }}<br><span style="color: #666;">{{ $items->count() }} garrafa(s) · {{ number_format($product->sale_price * $items->count(), 2, ',', '.') }} €</span></p>
            @if($product->discount_percentage > 0)
                <p style="margin-top: 0;"><strong>Desconto:</strong> {{ rtrim(rtrim(number_format($product->discount_percentage, 2, ',', '.'), '0'), ',') }}%</p>
            @endif
        @endforeach
        @php
            $productsTotal = $registrations->sum(fn ($item) => $item->event->sale_price);
            $shippingCost = $registration->order?->shipping_cost ?? $registration->shipping_cost ?? 0;
        @endphp
        <p><strong>Produtos:</strong> {{ number_format($productsTotal, 2, ',', '.') }} €</p>
        <p><strong>Envio:</strong> {{ $shippingCost ? number_format($shippingCost, 2, ',', '.') . ' €' : 'Grátis' }}</p>
        <p><strong>Total pago:</strong> {{ number_format($productsTotal + $shippingCost, 2, ',', '.') }} €</p>
    </div>

    <p style="text-align: center;">
          <a href="{{ route('registrations.show', $registration) }}" 
              style="background-color: #5b1820; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                Ver recibo da compra
        </a>
    </p>

    <p style="font-size: 12px; color: #777; margin-top: 30px;">
        Este é um email automático da Aroma Nobre. Guarda esta mensagem como comprovativo da tua compra. Os documentos fiscais serão tratados de acordo com os requisitos legais aplicáveis.
    </p>

</div>