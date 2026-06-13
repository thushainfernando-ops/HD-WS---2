<div class="py-12 sm:py-16">
    <div class="page-shell">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <span class="section-kicker">Cart</span>
                <h2 class="section-title mt-4">Review your order and continue to checkout.</h2>
            </div>
            <div class="flex items-center gap-3 rounded-full bg-white/90 px-4 py-2 shadow-sm">
                <i class="fas fa-lock text-primary-500"></i>
                <span class="text-sm font-semibold text-darkblue-700">Secure checkout</span>
            </div>
        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
            <div>
                @if($cartItems->count() > 0)
                    <div class="space-y-4">
                        @foreach($cartItems as $item)
                            <div class="card p-5">
                                <div class="flex flex-col gap-5 md:flex-row md:items-center">
                                    <img src="{{ asset($item->menuItem->image) }}" alt="{{ $item->menuItem->name }}" class="h-24 w-24 rounded-2xl object-cover">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-darkblue-700">{{ $item->menuItem->name }}</h3>
                                        <p class="mt-1 text-sm text-slate-600">Rs. {{ number_format($item->menuItem->price, 0) }} each</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <button wire:click="decrementQuantity({{ $item->cart_id }})" aria-label="Decrease quantity of {{ $item->menuItem->name }}" class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-700 transition hover:bg-slate-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                                            </svg>
                                        </button>
                                        <span class="min-w-8 text-center text-lg font-bold text-darkblue-700">{{ $item->quantity }}</span>
                                        <button wire:click="incrementQuantity({{ $item->cart_id }})" aria-label="Increase quantity of {{ $item->menuItem->name }}" class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-700 transition hover:bg-slate-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-black text-primary-500">Rs. {{ number_format($item->menuItem->price * $item->quantity, 0) }}</p>
                                        <button wire:click="removeItem({{ $item->cart_id }})" class="mt-2 text-sm font-semibold text-rose-500 transition hover:text-rose-600">Remove</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="card p-10 text-center">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-slate-100 text-3xl text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l3-8H5.4" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 13l-1.2 5.4A1 1 0 006.8 19h10.4a1 1 0 00.98-.8L19 9H7z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-xl font-bold text-darkblue-700">Your cart is empty</h3>
                        <p class="mt-2 text-sm leading-7 text-slate-600">Add a few dishes from the menu and come back here to review your order.</p>
                        <a href="/menu" class="btn-primary mt-6">Browse menu</a>
                    </div>
                @endif
            </div>

            @if($cartItems->count() > 0)
                <aside class="lg:sticky lg:top-24">
                    <div class="card p-8">
                        <h3 class="text-xl font-bold text-darkblue-700">Order summary</h3>
                        <div class="mt-6 space-y-4 text-sm text-slate-600">
                            <div class="flex justify-between"><span>Subtotal</span><span>Rs. {{ number_format($subtotal, 0) }}</span></div>
                            <div class="flex justify-between"><span>Delivery fee</span><span>Rs. {{ number_format($deliveryFee, 0) }}</span></div>
                            <div class="border-t pt-4 flex justify-between text-base font-bold text-darkblue-700">
                                <span>Total</span>
                                <span class="text-primary-500">Rs. {{ number_format($total, 0) }}</span>
                            </div>
                        </div>
                        <a href="/checkout" class="btn-primary mt-8 w-full">Proceed to checkout</a>
                    </div>
                </aside>
            @endif
        </div>
    </div>
</div>
