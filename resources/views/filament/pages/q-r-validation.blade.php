<x-filament-panels::page>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @foreach($this->events as $event)

            <a
                href="{{ \App\Filament\Pages\QRScanner::getUrl([
    'event' => $event['id']
]) }}"
                class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition border"
            >

                <h2 class="text-xl font-bold text-gray-900 mb-2">
                    {{ $event['title'] }}
                </h2>

                <p class="text-sm text-gray-500">
                    📍 {{ $event['city'] }}
                </p>

                <p class="text-sm text-gray-500 mt-1">
                    📅 {{ \Carbon\Carbon::parse($event['date'])->format('d/m/Y H:i') }}
                </p>

            </a>

        @endforeach

    </div>

</x-filament-panels::page>
