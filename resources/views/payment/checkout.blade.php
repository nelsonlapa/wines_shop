@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8">
    <div class="mb-10">
        <p class="wine-kicker mb-2">Último passo</p>
        <h1 class="font-serif text-5xl text-stone-900">Finalizar compra</h1>
        <p class="text-stone-500 mt-3">Confirma os teus dados e escolhe como queres receber a encomenda.</p>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-8 items-start" x-data="{ delivery: @js(old('delivery_method', 'delivery')), subtotal: {{ $subtotal }}, shipping() { return this.delivery === 'pickup' ? 0 : 5; }, money(value) { return value.toFixed(2).replace('.', ',') + ' €'; } }">
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
                        <label for="phone" class="block text-sm font-semibold text-stone-700 mb-2">Telemóvel</label>
                        <div class="flex gap-2">
                            <div x-data="{ open: false, search: '', selectedCode: @js(old('country_code', '+351')), selectedLabel: 'Portugal (+351)', choose(code, label) { this.selectedCode = code; this.selectedLabel = label; this.search = ''; this.open = false; }, matches(value) { return !this.search || value.toLowerCase().includes(this.search.toLowerCase()); } }" @click.outside="open = false" class="relative w-44 shrink-0">
                            <input type="hidden" id="country_code" name="country_code" x-model="selectedCode" required>
                            <button type="button" @click="open = !open" class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-left text-sm" aria-haspopup="listbox" :aria-expanded="open">
                                <span x-text="selectedLabel"></span>
                                <span class="ml-2 text-stone-500" aria-hidden="true">▾</span>
                            </button>
                            <div x-cloak x-show="open" x-transition class="absolute left-0 top-full z-50 mt-2 max-h-[22rem] w-[min(22rem,calc(100vw-3rem))] overflow-y-auto rounded-lg border border-stone-300 bg-white p-1 shadow-xl" role="listbox">
                                <input type="search" x-model="search" placeholder="Pesquisar país..." class="sticky top-0 z-10 mb-1 w-full rounded-md px-3 py-2 text-sm" aria-label="Pesquisar país ou indicativo">
                            @foreach([
                                'Afeganistão' => '+93', 'África do Sul' => '+27', 'Albânia' => '+355', 'Alemanha' => '+49', 'Andorra' => '+376', 'Angola' => '+244', 'Antígua e Barbuda' => '+1', 'Arábia Saudita' => '+966', 'Argélia' => '+213', 'Argentina' => '+54', 'Arménia' => '+374', 'Austrália' => '+61', 'Áustria' => '+43', 'Azerbaijão' => '+994',
                                'Bahamas' => '+1', 'Bangladesh' => '+880', 'Barbados' => '+1', 'Bélgica' => '+32', 'Belize' => '+501', 'Benim' => '+229', 'Bielorrússia' => '+375', 'Bolívia' => '+591', 'Bósnia e Herzegovina' => '+387', 'Botswana' => '+267', 'Brasil' => '+55', 'Brunei' => '+673', 'Bulgária' => '+359', 'Burquina Faso' => '+226', 'Burundi' => '+257',
                                'Cabo Verde' => '+238', 'Camarões' => '+237', 'Canadá' => '+1', 'Catar' => '+974', 'Cazaquistão' => '+7', 'Chade' => '+235', 'Chile' => '+56', 'China' => '+86', 'Chipre' => '+357', 'Colômbia' => '+57', 'Comores' => '+269', 'Congo' => '+242', 'Coreia do Sul' => '+82', 'Costa do Marfim' => '+225', 'Costa Rica' => '+506', 'Croácia' => '+385', 'Cuba' => '+53',
                                'Dinamarca' => '+45', 'Djibouti' => '+253', 'Dominica' => '+1', 'Egito' => '+20', 'El Salvador' => '+503', 'Emirados Árabes Unidos' => '+971', 'Equador' => '+593', 'Eslováquia' => '+421', 'Eslovénia' => '+386', 'Espanha' => '+34', 'Estados Unidos' => '+1', 'Estónia' => '+372', 'Etiópia' => '+251',
                                'Fiji' => '+679', 'Filipinas' => '+63', 'Finlândia' => '+358', 'França' => '+33', 'Gabão' => '+241', 'Gâmbia' => '+220', 'Gana' => '+233', 'Geórgia' => '+995', 'Gibraltar' => '+350', 'Granada' => '+1', 'Grécia' => '+30', 'Guatemala' => '+502', 'Guiana' => '+592', 'Guiné' => '+224', 'Guiné-Bissau' => '+245', 'Guiné Equatorial' => '+240', 'Haiti' => '+509', 'Honduras' => '+504', 'Hungria' => '+36',
                                'Iémen' => '+967', 'Ilhas Marshall' => '+692', 'Ilhas Salomão' => '+677', 'Índia' => '+91', 'Indonésia' => '+62', 'Irão' => '+98', 'Iraque' => '+964', 'Irlanda' => '+353', 'Islândia' => '+354', 'Israel' => '+972', 'Itália' => '+39', 'Jamaica' => '+1', 'Japão' => '+81', 'Jordânia' => '+962', 'Koweit' => '+965', 'Laos' => '+856', 'Lesoto' => '+266', 'Letónia' => '+371', 'Líbano' => '+961', 'Libéria' => '+231', 'Líbia' => '+218', 'Liechtenstein' => '+423', 'Lituânia' => '+370', 'Luxemburgo' => '+352',
                                'Madagáscar' => '+261', 'Malásia' => '+60', 'Malawi' => '+265', 'Maldivas' => '+960', 'Mali' => '+223', 'Malta' => '+356', 'Marrocos' => '+212', 'Maurícia' => '+230', 'Mauritânia' => '+222', 'México' => '+52', 'Mianmar' => '+95', 'Micronésia' => '+691', 'Moçambique' => '+258', 'Moldávia' => '+373', 'Mónaco' => '+377', 'Mongólia' => '+976', 'Montenegro' => '+382',
                                'Namíbia' => '+264', 'Nauru' => '+674', 'Nepal' => '+977', 'Nicarágua' => '+505', 'Níger' => '+227', 'Nigéria' => '+234', 'Noruega' => '+47', 'Nova Zelândia' => '+64', 'Omã' => '+968', 'Países Baixos' => '+31', 'Paquistão' => '+92', 'Palau' => '+680', 'Panamá' => '+507', 'Papua-Nova Guiné' => '+675', 'Paraguai' => '+595', 'Peru' => '+51', 'Polónia' => '+48', 'Portugal' => '+351',
                                'Quénia' => '+254', 'Quirguistão' => '+996', 'Reino Unido' => '+44', 'República Checa' => '+420', 'República Dominicana' => '+1', 'Roménia' => '+40', 'Ruanda' => '+250', 'Rússia' => '+7', 'Samoa' => '+685', 'Santa Lúcia' => '+1', 'São Marino' => '+378', 'São Tomé e Príncipe' => '+239', 'Senegal' => '+221', 'Sérvia' => '+381', 'Seicheles' => '+248', 'Serra Leoa' => '+232', 'Singapura' => '+65', 'Síria' => '+963', 'Somália' => '+252', 'Sri Lanka' => '+94', 'Suazilândia' => '+268', 'Sudão' => '+249', 'Suécia' => '+46', 'Suíça' => '+41',
                                'Tailândia' => '+66', 'Taiwan' => '+886', 'Tanzânia' => '+255', 'Timor-Leste' => '+670', 'Togo' => '+228', 'Tonga' => '+676', 'Trindade e Tobago' => '+1', 'Tunísia' => '+216', 'Turquia' => '+90', 'Tuvalu' => '+688', 'Ucrânia' => '+380', 'Uganda' => '+256', 'Uruguai' => '+598', 'Uzbequistão' => '+998', 'Vanuatu' => '+678', 'Vaticano' => '+39', 'Venezuela' => '+58', 'Vietname' => '+84', 'Zâmbia' => '+260', 'Zimbabwe' => '+263'
                            ] as $country => $code)
                                <button type="button" x-show="matches(@js($country . ' ' . $code))" @click="choose(@js($code), @js($country . ' (' . $code . ')'))" class="block w-full rounded-md px-3 py-2 text-left text-sm text-stone-700 hover:bg-stone-100">{{ $country }} ({{ $code }})</button>
                            @endforeach
                            </div>
                            </div>
                            <input id="phone" name="phone" value="{{ old('phone') }}" required class="min-w-0 flex-1 rounded-lg px-4 py-3" type="tel" autocomplete="tel-national" placeholder="912 345 678">
                        </div>
                        <p class="text-xs text-stone-500 mt-2">Seleciona o país e introduz o número sem o indicativo.</p>
                    </div>
                </div>
            </section>

            <section class="bg-white border border-stone-200 p-6">
                <p class="wine-kicker mb-2">Entrega</p>
                <h2 class="font-serif text-2xl text-stone-900 mb-5">Como queres receber?</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="cursor-pointer border border-stone-300 rounded-lg p-4 has-[:checked]:border-[#5b1820] has-[:checked]:ring-2 has-[:checked]:ring-[#5b1820]/20">
                        <input type="radio" name="delivery_method" value="delivery" x-model="delivery" @change="delivery = $event.target.value" @checked(old('delivery_method', 'delivery') === 'delivery') class="text-[#5b1820]">
                        <span class="ml-2 font-semibold text-stone-800">Entrega ao domicílio</span>
                        <span class="block text-sm text-stone-500 mt-2">5,00 €</span>
                    </label>
                    <label class="cursor-pointer border border-stone-300 rounded-lg p-4 has-[:checked]:border-[#5b1820] has-[:checked]:ring-2 has-[:checked]:ring-[#5b1820]/20">
                        <input type="radio" name="delivery_method" value="pickup" x-model="delivery" @change="delivery = $event.target.value" @checked(old('delivery_method') === 'pickup') class="text-[#5b1820]">
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
