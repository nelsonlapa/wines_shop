<div style="font-family: sans-serif; max-width: 560px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
    
    <h2 style="color: #5b1820;">Olá, {{ $registration->user->name }}!</h2>
    
    <p>A tua compra foi confirmada com sucesso. Obrigado por escolheres a Aroma Nobre.</p>
    
    <div style="background-color: #f3f4f6; padding: 15px; border-radius: 5px; margin: 20px 0;">
        <h3 style="margin-top: 0;">Recibo de compra</h3>
        <p><strong>Referência:</strong> #{{ $registration->id }}</p>
        <p><strong>Data:</strong> {{ $registration->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Produto:</strong> {{ $registration->event->title }}</p>
        <p><strong>Total pago:</strong> {{ number_format($registration->event->sale_price, 2, ',', '.') }} €</p>
        @if($registration->event->discount_percentage > 0)
            <p><strong>Desconto:</strong> {{ rtrim(rtrim(number_format($registration->event->discount_percentage, 2, ',', '.'), '0'), ',') }}%</p>
        @endif
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