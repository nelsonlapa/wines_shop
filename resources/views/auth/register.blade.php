<x-guest-layout>
    <div class="w-full max-w-lg mx-auto">

        <div class="mb-8 text-center">
            <p class="wine-kicker mb-3">Faça parte da nossa seleção</p>
            <h1 class="font-serif text-4xl text-stone-900">
                Criar conta
            </h1>

            <p class="mt-3 text-sm text-stone-500">
                Cria a tua conta para comprar vinhos e acompanhar as tuas encomendas.
            </p>
        </div>

        <div class="bg-white shadow-xl rounded-xl p-8 border border-stone-200">
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <x-input-label for="name" value="Nome" />

                    <x-text-input
                        id="name"
                        class="block mt-2 w-full"
                        type="text"
                        name="name"
                        :value="old('name')"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="O seu nome completo"
                    />

                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="email" value="Email" />

                    <x-text-input
                        id="email"
                        class="block mt-2 w-full"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autocomplete="username"
                        placeholder="exemplo@email.com"
                    />

                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password" value="Palavra-passe" />

                    <x-text-input
                        id="password"
                        class="block mt-2 w-full"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="Crie uma palavra-passe segura"
                    />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" value="Confirmar palavra-passe" />

                    <x-text-input
                        id="password_confirmation"
                        class="block mt-2 w-full"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Repita a palavra-passe"
                    />

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <button
                    type="submit"
                    class="wine-button w-full py-3 px-4 rounded-lg font-semibold hover:bg-[#771f2a] focus:outline-none focus:ring-2 focus:ring-[#5b1820] focus:ring-offset-2 transition"
                >
                    Criar conta
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-stone-500">
                    Já tem conta?

                    <a href="{{ route('login') }}"
                       class="font-semibold text-[#5b1820] hover:text-[#771f2a]">
                        Entrar
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>