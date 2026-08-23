<footer class="wine-footer text-stone-300 mt-20">
    <div class="container mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h2 class="text-xl font-bold text-white mb-4"><span class="brand-script text-amber-200">Vinha</span><span class="text-stone-100"> & Companhia</span></h2>
                <p class="text-sm text-stone-400">Garrafas com história, escolhidas para a sua mesa. Descubra o melhor de Portugal em cada gole.</p>
            </div>
            <div>
                <h3 class="text-white font-semibold mb-4">Categorias</h3>
                <ul class="space-y-2 text-sm">
                    @forelse ($footerCategories as $category)
                        <li><a href="{{ route('events.category', $category) }}" class="hover:text-white">{{ $category->name }}</a></li>
                    @empty
                        <li class="text-stone-500">Sem categorias disponíveis</li>
                    @endforelse
                </ul>
            </div>
            <div>
                <h3 class="text-white font-semibold mb-4">Contacto</h3>
                <p class="text-sm text-stone-400">Porto, Portugal</p>
                <p class="text-sm text-stone-400 mt-2">hello@vinha.pt</p>
                <p class="text-sm text-stone-400 mt-2">+351 220 000 000</p>
            </div>
        </div>
        <div class="border-t border-stone-800 mt-10 pt-6 flex flex-col md:flex-row justify-between items-center">
            <p class="text-sm text-stone-500">© {{ date('Y') }} Vinha & Companhia. Beba com moderação.</p>
            <div class="flex space-x-4 mt-4 md:mt-0"><a href="#" class="hover:text-white">Instagram</a><a href="#" class="hover:text-white">Facebook</a></div>
        </div>
    </div>
</footer>
