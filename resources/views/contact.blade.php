@extends('layouts.frontend')

@section('content')
<div class="py-12 sm:py-16">
    <div class="page-shell">
        <div class="text-center">
            <span class="section-kicker">Contact</span>
            <h2 class="section-title mt-4">Get in touch with the Hot Dish team</h2>
            <p class="mx-auto mt-4 max-w-2xl text-base leading-8 text-slate-600">
                Whether you have questions about the menu, your order, or working with us, we'd love to hear from you.
            </p>
        </div>

        <div class="mt-10 grid gap-6 lg:grid-cols-[1.05fr_0.95fr]">
            <div class="card p-8">
                <form action="#" class="space-y-5">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-darkblue-700">Full Name</label>
                        <input type="text" class="input-field" placeholder="John Doe">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-darkblue-700">Email Address</label>
                        <input type="email" class="input-field" placeholder="john@example.com">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-darkblue-700">Message</label>
                        <textarea class="input-field h-36" placeholder="Tell us how we can help"></textarea>
                    </div>
                    <button type="submit" class="btn-primary w-full">Send message</button>
                </form>
            </div>

            <div class="space-y-5">
                <div class="glass-panel p-6">
                    <h3 class="text-xl font-bold text-darkblue-700">Visit us</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">123 Galle Road, Colombo 03, Sri Lanka</p>
                </div>
                <div class="glass-panel p-6">
                    <h3 class="text-xl font-bold text-darkblue-700">Opening hours</h3>
                    <ul class="mt-3 space-y-2 text-sm text-slate-600">
                        <li>Monday - Friday: 10:00 AM - 10:00 PM</li>
                        <li>Saturday - Sunday: 11:00 AM - 11:00 PM</li>
                    </ul>
                </div>
                <div class="glass-panel p-6">
                    <h3 class="text-xl font-bold text-darkblue-700">Need fast help?</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Call us at +94 11 234 5678 or email info@hotdish.lk and our support team will respond promptly.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
