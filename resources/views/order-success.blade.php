@extends('layouts.frontend')

@section('content')
<div class="py-12 sm:py-16">
    <div class="page-shell">
        <div class="mx-auto max-w-3xl text-center">
            <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-emerald-100 text-4xl text-emerald-600">
                <i class="fas fa-check"></i>
            </div>
            <h2 class="mt-6 text-3xl font-black tracking-tight text-darkblue-700 sm:text-4xl">Order confirmed!</h2>
            <p class="mt-4 text-base leading-8 text-slate-600">
                Thank you for choosing Hot Dish. Your order #{{ $orderId }} has been received and is being prepared for delivery.
            </p>

            <div class="card mt-8 p-8 text-left">
                <h3 class="text-lg font-bold text-darkblue-700">What happens next?</h3>
                <div class="mt-5 space-y-4">
                    <div class="flex gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary-100 text-sm font-bold text-primary-600">1</div>
                        <p class="text-sm leading-7 text-slate-600">Our chefs prepare your selected dishes with care and freshness.</p>
                    </div>
                    <div class="flex gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary-100 text-sm font-bold text-primary-600">2</div>
                        <p class="text-sm leading-7 text-slate-600">A delivery partner is assigned and your order is dispatched.</p>
                    </div>
                    <div class="flex gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary-100 text-sm font-bold text-primary-600">3</div>
                        <p class="text-sm leading-7 text-slate-600">Your food arrives hot and fresh within 30–45 minutes.</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                <a href="/menu" class="btn-primary">Order more</a>
                <a href="/" class="btn-outline">Go to home</a>
            </div>
        </div>
    </div>
</div>
@endsection
