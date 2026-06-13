@extends('layouts.frontend')

@section('content')
<div class="py-12 sm:py-16">
    <div class="page-shell">
        <div class="text-center">
            <span class="section-kicker">Checkout</span>
            <h2 class="section-title mt-4">Complete your order</h2>
            <p class="mx-auto mt-3 max-w-2xl text-base leading-8 text-slate-600">Confirm your delivery details and choose the payment method that suits you best.</p>
        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="card p-8">
                <h3 class="text-xl font-bold text-darkblue-700">Delivery information</h3>

                @if(session('error') || $errors->any())
                    <div class="mb-6 rounded-3xl border border-rose-200 bg-rose-50 p-4 text-rose-700">
                        @if(session('error'))
                            <p class="font-semibold">{{ session('error') }}</p>
                        @endif
                        @if($errors->any())
                            <ul class="mt-3 list-disc list-inside space-y-1 text-sm text-rose-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST" class="mt-6 space-y-5">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-darkblue-700">Delivery address</label>
                        <input type="text" name="address" required class="input-field" placeholder="123 Street Name, Colombo" value="{{ old('address') }}">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-darkblue-700">Phone number</label>
                        <input type="text" name="phone" value="{{ auth()->user()->phone }}" required class="input-field">
                    </div>

                    <div class="pt-4">
                        <h3 class="text-xl font-bold text-darkblue-700">Payment method</h3>
                        <div class="mt-4 space-y-3">
                            <label class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 transition hover:border-primary-200 hover:bg-primary-50">
                                <input type="radio" name="payment_method" value="card" checked class="text-primary-500 focus:ring-primary-500">
                                <span class="flex-1 font-semibold text-darkblue-700">Credit/Debit Card</span>
                                <i class="fas fa-cc-visa text-lg text-slate-400"></i>
                            </label>
                            <label class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 transition hover:border-primary-200 hover:bg-primary-50">
                                <input type="radio" name="payment_method" value="cash" class="text-primary-500 focus:ring-primary-500">
                                <span class="flex-1 font-semibold text-darkblue-700">Cash on Delivery</span>
                                <i class="fas fa-money-bill-wave text-lg text-slate-400"></i>
                            </label>
                        </div>
                    </div>

                    <!-- Card element (Stripe Elements) -->
                    <div id="card-section" class="mt-4 hidden">
                        <label class="mb-2 block text-sm font-semibold text-darkblue-700">Card details</label>
                        <div id="card-element" class="rounded-2xl border border-slate-200 bg-white p-4"></div>
                        <p id="card-errors" class="mt-2 text-sm text-rose-600" role="alert"></p>
                        <input type="hidden" name="stripe_token" id="stripe_token" />
                    </div>

                    <button type="submit" class="btn-primary w-full py-4 text-base">Place order</button>
                </form>
            </div>

            <div class="space-y-5">
                <div class="card p-8">
                    <h3 class="text-xl font-bold text-darkblue-700">Your order</h3>
                    @php
                        $cartItems = \App\Models\CartItem::where('user_id', auth()->id())->with('menuItem')->get();
                        $subtotal = $cartItems->sum(function($item) { return $item->menuItem->price * $item->quantity; });
                        $total = $subtotal + 250;
                    @endphp
                    <div class="mt-6 space-y-4">
                        @foreach($cartItems as $item)
                            <div class="flex items-center justify-between gap-4 rounded-2xl bg-slate-50 px-4 py-3">
                                <div>
                                    <p class="font-semibold text-darkblue-700">{{ $item->quantity }}x {{ $item->menuItem->name }}</p>
                                    <p class="text-sm text-slate-500">Rs. {{ number_format($item->menuItem->price, 0) }} each</p>
                                </div>
                                <p class="font-bold text-primary-500">Rs. {{ number_format($item->menuItem->price * $item->quantity, 0) }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 space-y-3 border-t pt-4 text-sm text-slate-600">
                        <div class="flex justify-between"><span>Subtotal</span><span>Rs. {{ number_format($subtotal, 0) }}</span></div>
                        <div class="flex justify-between"><span>Delivery fee</span><span>Rs. 250</span></div>
                        <div class="flex justify-between text-base font-bold text-darkblue-700"><span>Total</span><span class="text-primary-500">Rs. {{ number_format($total, 0) }}</span></div>
                    </div>
                </div>

                <div class="glass-panel p-6">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-info-circle text-primary-500"></i>
                        <p class="text-sm leading-7 text-slate-600">Your order will be prepared immediately after confirmation. Estimated delivery time is 30–45 minutes.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    (function(){
        const stripePublicKey = "{{ config('services.stripe.public_key') }}" || '';
        const form = document.getElementById('checkout-form');
        const cardSection = document.getElementById('card-section');
        const paymentRadios = document.querySelectorAll('input[name="payment_method"]');

        // show/hide card section
        function toggleCardSection(){
            const selected = document.querySelector('input[name="payment_method"]:checked').value;
            if(selected === 'card') cardSection.classList.remove('hidden'); else cardSection.classList.add('hidden');
        }

        paymentRadios.forEach(r => r.addEventListener('change', toggleCardSection));
        toggleCardSection();

        if (!stripePublicKey) return;

        const stripe = Stripe(stripePublicKey);
        const elements = stripe.elements();
        const style = {
            base: { color: '#0f172a', fontSize: '16px' },
        };
        const card = elements.create('card', { style });
        card.mount('#card-element');

        const cardErrors = document.getElementById('card-errors');

        form.addEventListener('submit', async function(e){
            const selected = document.querySelector('input[name="payment_method"]:checked').value;
            if (selected !== 'card') return; // proceed normally for cash

            e.preventDefault();
            const result = await stripe.createToken(card);
            if (result.error) {
                cardErrors.textContent = result.error.message;
                return;
            }

            document.getElementById('stripe_token').value = result.token.id;
            form.submit();
        });
    })();
</script>
@endpush
