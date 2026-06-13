<div class="py-12 sm:py-16">
    <div class="page-shell">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <span class="section-kicker">Menu</span>
                <h2 class="section-title mt-4">Build your feast with the best of Hot Dish.</h2>
                <p class="mt-3 max-w-2xl text-base leading-8 text-slate-600">Search for favorites, filter by category, and add your next meal in seconds.</p>
            </div>

            <div class="flex w-full max-w-xl items-center gap-3 rounded-[24px] border border-white/80 bg-white/90 px-4 py-3 shadow-[0_20px_50px_-30px_rgba(44,62,80,0.35)]">
                <i class="fas fa-search text-slate-400"></i>
                <input wire:model.live="search" type="text" placeholder="Search dishes or ingredients" class="w-full border-0 bg-transparent text-sm outline-none placeholder:text-slate-400">
            </div>
        </div>

        <div class="mt-8 flex flex-wrap gap-3">
            <button wire:click="setCategory('all')" class="menu-chip {{ $activeCategory === 'all' ? 'active' : 'inactive' }}">
                All dishes
            </button>
            @foreach($categories as $category)
                <button wire:click="setCategory('{{ $category }}')" class="menu-chip {{ $activeCategory === $category ? 'active' : 'inactive' }}">
                    {{ ucwords(str_replace('-', ' ', $category)) }}
                </button>
            @endforeach
        </div>

        @if(session()->has('message'))
            <div class="mt-6 flex items-center gap-3 rounded-2xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                <i class="fas fa-check-circle"></i>
                {{ session('message') }}
            </div>
        @endif

        <div class="mt-8 grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
            @forelse($items as $item)
                <article class="card group overflow-hidden">
                    <div class="relative h-44 overflow-hidden">
                        <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                        <span class="absolute left-4 top-4 rounded-full bg-darkblue-700/85 px-3 py-1 text-[10px] font-bold uppercase tracking-[0.3em] text-white">{{ $item->category }}</span>
                    </div>
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-darkblue-700">{{ $item->name }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $item->description }}</p>
                        <div class="mt-5 flex items-center justify-between">
                            <span class="text-xl font-black text-primary-500">Rs. {{ number_format($item->price, 0) }}</span>
                            <button wire:click="addToCart({{ $item->item_id }})" aria-label="Add {{ $item->name }} to cart" class="flex h-11 w-11 items-center justify-center rounded-full bg-primary-500 text-white shadow-lg shadow-primary-500/30 transition hover:bg-primary-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-[28px] border border-dashed border-slate-200 bg-white/80 px-6 py-16 text-center">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-slate-100 text-3xl text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-xl font-bold text-darkblue-700">No dishes found</h3>
                    <p class="mt-2 text-sm leading-7 text-slate-600">Try another keyword or switch to a different category.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
