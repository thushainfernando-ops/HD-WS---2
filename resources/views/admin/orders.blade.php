@extends('layouts.admin')

@section('content')
@php
    $orders = \App\Models\Order::with('user')->latest('order_id')->paginate(8);
@endphp

<div class="rounded-[28px] border border-white/10 bg-white/5 p-6 backdrop-blur">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-primary-200">Admin orders</p>
            <h2 class="mt-2 text-2xl font-bold text-white">Order overview</h2>
        </div>
        <span class="rounded-full border border-white/10 px-3 py-1 text-sm text-slate-100">{{ $orders->total() }} records</span>
    </div>

    <div class="mt-6 overflow-x-auto">
        <table class="min-w-full divide-y divide-white/10 text-left text-sm">
            <thead class="text-slate-200">
                <tr>
                    <th class="px-4 py-3 font-semibold">Order #</th>
                    <th class="px-4 py-3 font-semibold">Customer</th>
                    <th class="px-4 py-3 font-semibold">Date</th>
                    <th class="px-4 py-3 font-semibold">Total</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                @forelse ($orders as $order)
                    <tr>
                        <td class="px-4 py-4 font-bold text-white">#{{ $order->order_id }}</td>
                        <td class="px-4 py-4 text-slate-200">{{ $order->user->name ?? 'Guest customer' }}</td>
                        <td class="px-4 py-4 text-slate-200">{{ $order->created_at->format('M d, Y H:i') }}</td>
                        <td class="px-4 py-4 text-slate-200">{{ number_format($order->total_amount, 2) }} LKR</td>
                        <td class="px-4 py-4">
                            <span class="inline-flex rounded-full bg-primary-500/10 px-3 py-1 text-xs font-bold uppercase tracking-[0.2em] text-primary-100">
                                {{ $order->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-300">No orders have been placed yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection
