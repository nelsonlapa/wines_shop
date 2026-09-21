<nav class="absolute top-0 left-0 w-full z-50 py-5">
    <div class="max-w-7xl mx-auto px-6">
        <div class="wine-nav px-5 sm:px-7 py-4 flex items-center justify-between gap-6">

            <div class="flex items-center gap-5">


                <a href="{{ route('home') }}" class="brand-mark text-white whitespace-nowrap">
                    <span class="brand-script">Aroma</span><span class="text-amber-200"> Nobre</span>
                </a>
            </div>

            <div class="h-10 w-px bg-gray-200 hidden lg:block"></div>

            <div class="hidden lg:flex items-center gap-7 text-white/85 text-sm font-medium">
                <a href="{{ route('catalog.index') }}" class="text-base font-semibold hover:text-amber-200 transition">Catálogo</a>
            </div>

            <div class="h-10 w-px bg-gray-200 hidden xl:block"></div>

            <div class="flex items-center gap-4">
                <button type="button" x-data @click="document.documentElement.classList.toggle('dark'); localStorage.setItem('aroma-theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light')" class="theme-toggle inline-flex items-center justify-center w-10 h-10 rounded-full border border-white/30 text-white hover:bg-white/10 transition" aria-label="Alternar tema">
                    <span class="dark:hidden" aria-hidden="true">☾</span>
                    <span class="hidden dark:inline" aria-hidden="true">☀</span>
                </button>
                @auth
                    @if(auth()->user()->role && in_array(auth()->user()->role->name, ['admin', 'organizador']))
                        <a href="{{ route('checkin.scanner') }}"
                           class="inline-flex items-center gap-2 border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-2 rounded-full hover:bg-emerald-100 transition font-semibold whitespace-nowrap">
                            <span>Validar pedidos</span>
                        </a>
                        <a href="{{ url('/welldone') }}"
                            class="inline-flex items-center gap-2 border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-2 rounded-full hover:bg-emerald-100 transition font-semibold whitespace-nowrap">
                            <span>Gestão da loja</span>
                        </a>
                    @endif

                    <a href="{{ route('cart.index') }}"
                       class="inline-flex items-center gap-2 border border-amber-200 bg-amber-50 text-amber-800 px-4 py-2 rounded-full hover:bg-amber-100 transition font-semibold whitespace-nowrap">
                        <span>Carrinho</span>
                    </a>

                    <div class="h-10 w-px bg-gray-200 hidden xl:block"></div>

                    <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                        <button type="button"
                            @click="open = !open"
                            class="inline-flex items-center gap-3 border border-gray-200 bg-white px-4 py-2 rounded-full hover:bg-gray-50 transition shadow-sm whitespace-nowrap">
                        <span class="w-8 h-8 rounded-full bg-amber-800 text-white flex items-center justify-center">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>

                        <span class="text-gray-700 font-medium">
                            {{ auth()->user()->name }}
                        </span>

                        <span class="text-gray-500">
                            ▾
                        </span>
                        </button>

                        <div x-cloak x-show="open" x-transition
                            class="absolute right-0 mt-3 w-52 rounded-xl border border-stone-200 bg-white p-2 shadow-xl z-50">
                            <a href="{{ route('registrations.my') }}"
                               class="block rounded-lg px-4 py-3 text-sm font-semibold text-stone-700 hover:bg-stone-100 transition">
                                As minhas compras
                            </a>
                        </div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf

                        <button type="submit"
                                class="inline-flex items-center gap-2 border border-red-200 bg-red-50 text-red-600 px-4 py-2 rounded-full hover:bg-red-100 transition font-semibold whitespace-nowrap">
                            <span>Logout</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="text-white/90 hover:text-amber-200 font-medium transition">
                        Entrar
                    </a>

                          <a href="{{ route('register') }}"
                              class="account-cta bg-amber-200 text-stone-900 px-5 py-3 rounded-full hover:bg-amber-100 transition font-semibold shadow-sm">
                        Criar conta
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
