@extends('layouts.admin')

@section('content')
@php
    $totalOrders = \App\Models\Order::count();
    $processingOrders = \App\Models\Order::where('status', 'processing')->count();
    $totalUsers = \App\Models\User::where('role', 'user')->count();
    $menuItems = \App\Models\MenuItem::count();
    $recentOrders = \App\Models\Order::with('user')->latest('order_id')->take(5)->get();
@endphp

<div class="space-y-6">
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 backdrop-blur">
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-primary-200">Orders</p>
            <p class="mt-4 text-3xl font-black text-white">{{ $totalOrders }}</p>
            <p class="mt-2 text-sm text-slate-300">All customer orders in the system.</p>
        </div>
        <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 backdrop-blur">
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-primary-200">Processing</p>
            <p class="mt-4 text-3xl font-black text-white">{{ $processingOrders }}</p>
            <p class="mt-2 text-sm text-slate-300">Orders currently in progress.</p>
        </div>
        <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 backdrop-blur">
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-primary-200">Menu items</p>
            <p class="mt-4 text-3xl font-black text-white">{{ $menuItems }}</p>
            <p class="mt-2 text-sm text-slate-300">Dishes available to customers.</p>
        </div>
        <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 backdrop-blur">
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-primary-200">Customers</p>
            <p class="mt-4 text-3xl font-black text-white">{{ $totalUsers }}</p>
            <p class="mt-2 text-sm text-slate-300">Registered customer accounts.</p>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
        <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 backdrop-blur">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-primary-200">Recent activity</p>
                    <h2 class="mt-2 text-xl font-bold text-white">Latest orders</h2>
                </div>
                <a href="{{ route('admin.orders') }}" class="text-sm font-semibold text-primary-200 hover:text-primary-100">Open orders</a>
            </div>

            <div class="mt-5 space-y-3">
                @forelse ($recentOrders as $order)
                    <div class="rounded-[24px] border border-white/10 bg-slate-950/60 px-4 py-4">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="font-bold text-white">Order #{{ $order->order_id }}</p>
                                <p class="text-sm text-slate-300">{{ $order->user->name ?? 'Guest customer' }} • {{ $order->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-primary-200">{{ number_format($order->total_amount, 2) }} LKR</p>
                                <p class="text-sm text-slate-300 capitalize">{{ $order->status }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-[24px] border border-dashed border-white/10 px-4 py-8 text-center text-sm text-slate-300">
                        No recent activity yet.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 backdrop-blur">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-primary-200">Admin tools</p>
                <h2 class="mt-2 text-xl font-bold text-white">Control your store</h2>
                <div class="mt-5 space-y-3">
                    <a href="{{ route('admin.menu') }}" class="block rounded-[24px] bg-primary-500/10 px-4 py-4">
                        <p class="font-semibold text-white">Manage menu items</p>
                        <p class="mt-1 text-sm text-slate-300">Add, edit or remove dishes from the menu.</p>
                    </a>
                    <a href="{{ route('admin.customers') }}" class="block rounded-[24px] bg-slate-900/80 px-4 py-4">
                        <p class="font-semibold text-white">Manage customers</p>
                        <p class="mt-1 text-sm text-slate-300">Review customer records and update roles.</p>
                    </a>
                    <a href="{{ route('admin.orders') }}" class="block rounded-[24px] bg-slate-900/80 px-4 py-4">
                        <p class="font-semibold text-white">Review orders</p>
                        <p class="mt-1 text-sm text-slate-300">Track order status and customer activity.</p>
                    </a>
                </div>
            </div>

            <div class="rounded-[28px] border border-white/10 bg-white/5 p-6 backdrop-blur">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-primary-200">Security note</p>
                <p class="mt-3 text-sm leading-7 text-slate-300">Use strong credentials for the admin account and rotate the password after launch.</p>
                <div class="mt-4 rounded-[24px] bg-amber-500/10 px-4 py-3 text-sm text-amber-100">
                    Current admin account: admin@hotdish.com • admin123
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
