@extends('layouts.app')

@section('content')

<div class="max-w-xl mx-auto bg-white shadow-lg rounded-xl p-6 text-center">

    <h1 class="text-2xl font-bold mb-4">
        Validar QR Code
    </h1>

    <p class="text-gray-600 mb-6">
        Aponte a câmara para o QR Code do bilhete do participante.
    </p>

    <div id="reader" class="w-full"></div>

    <p id="scan-result" class="mt-4 text-sm text-gray-600"></p>

</div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    let alreadyScanned = false;

    function onScanSuccess(decodedText) {
        if (alreadyScanned) {
            return;
        }

        alreadyScanned = true;

        document.getElementById('scan-result').innerText = 'QR Code lido. A validar...';

        if (decodedText.includes('/checkin/')) {
            window.location.href = decodedText;
        } else {
            document.getElementById('scan-result').innerText = 'QR Code inválido.';
            alreadyScanned = false;
        }
    }

    function onScanFailure(error) {
        // Não é necessário mostrar erro a cada tentativa falhada.
    }

    const scanner = new Html5QrcodeScanner(
        "reader",
        {
            fps: 10,
            qrbox: 250
        },
        false
    );

    scanner.render(onScanSuccess, onScanFailure);
</script>

@endsection
