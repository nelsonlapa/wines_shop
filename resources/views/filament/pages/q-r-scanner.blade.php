
<x-filament-panels::page>

    <div class="max-w-2xl mx-auto">

        <div class="bg-white rounded-2xl shadow p-6">

            <h1 class="text-2xl font-bold mb-2">
                {{ $this->event->title }}
            </h1>

            <p class="text-gray-500 mb-6">
                Aponte a câmara para o QR Code.
            </p>

            <div id="reader"></div>

            <div
                id="result"
                class="mt-6 text-center text-lg font-semibold"
            ></div>

        </div>

    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>

    <script>

        function onScanSuccess(decodedText)
        {
            document.getElementById('result')
                .innerHTML =
                'QR lido: ' + decodedText;
        }

        function onScanFailure(error)
        {
        }

        const scanner = new Html5QrcodeScanner(
            "reader",
            {
                fps: 10,
                qrbox: 250
            },
            false
        );

        scanner.render(
            onScanSuccess,
            onScanFailure
        );

    </script>

</x-filament-panels::page>
