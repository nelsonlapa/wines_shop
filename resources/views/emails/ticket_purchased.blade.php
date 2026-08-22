<div style="font-family: sans-serif; max-w-md; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
    
    <h2 style="color: #4F46E5;">Olá, {{ $registration->user->name }}!</h2>
    
    <p>O teu pagamento foi confirmado com sucesso. O teu lugar no evento está garantido.</p>
    
    <div style="background-color: #f3f4f6; padding: 15px; border-radius: 5px; margin: 20px 0;">
        <h3 style="margin-top: 0;">{{ $registration->event->title }}</h3>
        <p><strong>Data:</strong> {{ $registration->event->date->format('d/m/Y H:i') }}</p>
        <p><strong>Local:</strong> {{ $registration->event->city }}</p>
        <p><strong>Código do Bilhete:</strong> {{ $registration->ticket_token }}</p>
    </div>

    <p style="text-align: center;">
        <a href="{{ route('registrations.show', $registration) }}" 
           style="background-color: #4F46E5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">
            Ver e Imprimir Bilhete
        </a>
    </p>

    <p style="font-size: 12px; color: #777; margin-top: 30px;">
        Este é um email automático, por favor não respondas. Mostra o QR Code do link acima na entrada do evento.
    </p>

</div>