<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-primary-600">Dashboard</p>
                <h2 class="mt-2 text-2xl font-bold text-darkblue-700">Welcome back, {{ auth()->user()->name }}.</h2>
            </div>
            <a href="/menu" class="btn-primary">Explore menu</a>
        </div>
    </x-slot>

    @php
        $cartCount = \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity');
        $orderCount = \App\Models\Order::where('user_id', auth()->id())->count();
        $latestOrder = \App\Models\Order::where('user_id', auth()->id())->latest('order_id')->first();
    @endphp

    <div class="py-10">
        <div class="page-shell">
            <div class="grid gap-5 md:grid-cols-3">
                <div class="card p-6">
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-primary-600">Cart items</p>
                    <p class="mt-4 text-3xl font-black text-darkblue-700">{{ $cartCount }}</p>
                    <p class="mt-2 text-sm text-slate-600">Items waiting for checkout.</p>
                </div>
                <div class="card p-6">
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-primary-600">Orders</p>
                    <p class="mt-4 text-3xl font-black text-darkblue-700">{{ $orderCount }}</p>
                    <p class="mt-2 text-sm text-slate-600">Completed and active orders.</p>
                </div>
                <div class="card p-6">
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-primary-600">Current status</p>
                    <p class="mt-4 text-lg font-bold text-darkblue-700">{{ $latestOrder ? strtoupper($latestOrder->status) : 'NO ORDERS YET' }}</p>
                    <p class="mt-2 text-sm text-slate-600">Track your latest order progress here.</p>
                </div>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="card p-8">
                    <h3 class="text-xl font-bold text-darkblue-700">Quick actions</h3>
                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        <a href="/menu" class="rounded-[24px] bg-primary-50 p-5 transition hover:bg-primary-100">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-primary-600">Browse</p>
                            <p class="mt-3 text-lg font-bold text-darkblue-700">Menu</p>
                        </a>
                        <a href="/cart" class="rounded-[24px] bg-slate-50 p-5 transition hover:bg-slate-100">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-primary-600">Manage</p>
                            <p class="mt-3 text-lg font-bold text-darkblue-700">Cart</p>
                        </a>
                        <a href="/checkout" class="rounded-[24px] bg-slate-50 p-5 transition hover:bg-slate-100">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-primary-600">Finish</p>
                            <p class="mt-3 text-lg font-bold text-darkblue-700">Checkout</p>
                        </a>
                        <a href="/profile/show" class="rounded-[24px] bg-slate-50 p-5 transition hover:bg-slate-100">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-primary-600">Update</p>
                            <p class="mt-3 text-lg font-bold text-darkblue-700">Profile</p>
                        </a>
                    </div>
                </div>

                <div class="card p-8">
                    <h3 class="text-xl font-bold text-darkblue-700">Why Hot Dish?</h3>
                    <ul class="mt-5 space-y-4 text-sm leading-7 text-slate-600">
                        <li class="flex gap-3"><i class="fas fa-check text-primary-500"></i><span>Modern ordering flow with secure authentication.</span></li>
                        <li class="flex gap-3"><i class="fas fa-check text-primary-500"></i><span>Laravel Jetstream and Sanctum ready for robust access control.</span></li>
                        <li class="flex gap-3"><i class="fas fa-check text-primary-500"></i><span>Clean, responsive UI built around your navy-blue, white, and orange identity.</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
