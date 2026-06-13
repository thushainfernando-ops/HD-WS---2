@extends('layouts.frontend')

@section('title', 'My Account - Hot Dish')

@section('content')
@php
    $cartCount = \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity');
    $orderCount = \App\Models\Order::where('user_id', auth()->id())->count();
    $latestOrder = \App\Models\Order::where('user_id', auth()->id())->latest('order_id')->first();
@endphp

<div class="bg-[radial-gradient(circle_at_top_left,_rgba(244,114,182,0.12),transparent_20%),linear-gradient(180deg,#0b1120_0%,#111827_45%,#0b1120_100%)]">
    <div class="page-shell py-8 sm:py-12">
        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-primary-200">My account</p>
                <h1 class="mt-3 text-3xl font-black text-white sm:text-4xl">Welcome back, {{ auth()->user()->name }}.</h1>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-200 sm:text-base">View your cart, check your latest order status, and manage your account details from one polished dashboard.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('menu') }}" class="rounded-full bg-primary-500 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-primary-500/30 transition hover:bg-primary-600">Browse menu</a>
                <a href="{{ route('cart') }}" class="rounded-full border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10">View cart</a>
            </div>
        </div>

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 backdrop-blur">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-primary-200">Cart</p>
                <p class="mt-4 text-3xl font-black text-white">{{ $cartCount }}</p>
                <p class="mt-2 text-sm text-slate-300">Items ready for checkout.</p>
            </div>
            <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 backdrop-blur">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-primary-200">Orders</p>
                <p class="mt-4 text-3xl font-black text-white">{{ $orderCount }}</p>
                <p class="mt-2 text-sm text-slate-300">Orders placed so far.</p>
            </div>
            <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 backdrop-blur">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-primary-200">Status</p>
                <p class="mt-4 text-lg font-black text-white">{{ $latestOrder ? strtoupper($latestOrder->status) : 'NO ORDERS YET' }}</p>
                <p class="mt-2 text-sm text-slate-300">Your latest order progress.</p>
            </div>
            <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 backdrop-blur">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-primary-200">Account</p>
                <p class="mt-4 text-lg font-black text-white">{{ auth()->user()->role === 'admin' ? 'Admin access' : 'Customer account' }}</p>
                <p class="mt-2 text-sm text-slate-300">{{ auth()->user()->email }}</p>
            </div>
        </div>

        <div class="mt-8 grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
            <div class="space-y-6">
                <section class="rounded-[28px] border border-white/10 bg-white/5 p-6 backdrop-blur">
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-primary-200">Quick actions</p>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        <a href="{{ route('menu') }}" class="rounded-[24px] bg-primary-500/10 px-4 py-4 transition hover:bg-primary-500/20">
                            <p class="font-bold text-white">Explore menu</p>
                            <p class="mt-1 text-sm text-slate-200">Discover your next favorite meal.</p>
                        </a>
                        <a href="{{ route('cart') }}" class="rounded-[24px] bg-slate-900/80 px-4 py-4 transition hover:bg-slate-800">
                            <p class="font-bold text-white">Review cart</p>
                            <p class="mt-1 text-sm text-slate-200">Check totals and quantities before checkout.</p>
                        </a>
                        <a href="{{ route('checkout') }}" class="rounded-[24px] bg-slate-900/80 px-4 py-4 transition hover:bg-slate-800">
                            <p class="font-bold text-white">Checkout</p>
                            <p class="mt-1 text-sm text-slate-200">Complete your order in a few clicks.</p>
                        </a>
                        <a href="{{ route('contact') }}" class="rounded-[24px] bg-slate-900/80 px-4 py-4 transition hover:bg-slate-800">
                            <p class="font-bold text-white">Need help?</p>
                            <p class="mt-1 text-sm text-slate-200">Reach out to our support team.</p>
                        </a>
                    </div>
                </section>

                @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                    <section class="rounded-[28px] border border-white/10 bg-white/5 p-6 backdrop-blur">
                        @livewire('profile.update-profile-information-form')
                    </section>
                @endif

                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                    <section class="rounded-[28px] border border-white/10 bg-white/5 p-6 backdrop-blur">
                        @livewire('profile.update-password-form')
                    </section>
                @endif

                @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                    <section class="rounded-[28px] border border-white/10 bg-white/5 p-6 backdrop-blur">
                        @livewire('profile.two-factor-authentication-form')
                    </section>
                @endif
            </div>

            <div class="space-y-6">
                <section class="rounded-[28px] border border-white/10 bg-white/5 p-6 backdrop-blur">
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-primary-200">Latest order</p>
                    @if ($latestOrder)
                        <div class="mt-4 rounded-[24px] bg-slate-950/70 p-4">
                            <p class="text-lg font-bold text-white">Order #{{ $latestOrder->order_id }}</p>
                            <p class="mt-2 text-sm text-slate-200">Placed on {{ $latestOrder->created_at->format('M d, Y H:i') }}</p>
                            <p class="mt-3 text-sm font-semibold uppercase tracking-[0.2em] text-primary-200">{{ strtoupper($latestOrder->status) }}</p>
                        </div>
                    @else
                        <p class="mt-4 text-sm text-slate-200">You have not placed any orders yet. Start by exploring the menu.</p>
                    @endif
                </section>

                <section class="rounded-[28px] border border-white/10 bg-white/5 p-6 backdrop-blur">
                    @livewire('profile.logout-other-browser-sessions-form')
                </section>

                @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                    <section class="rounded-[28px] border border-white/10 bg-white/5 p-6 backdrop-blur">
                        @livewire('profile.delete-user-form')
                    </section>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
