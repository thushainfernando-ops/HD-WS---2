@extends('layouts.frontend')

@section('content')
<div class="py-12 sm:py-16">
    <div class="page-shell">
        <div class="grid items-center gap-10 lg:grid-cols-2">
            <div>
                <span class="section-kicker">About Hot Dish</span>
                <h2 class="section-title mt-4">A modern Sri Lankan dining experience, rooted in tradition.</h2>
                <p class="mt-4 text-base leading-8 text-slate-600">
                    Hot Dish was created to bring the bold, layered flavors of Sri Lankan food into a clean, easy-to-use digital experience. We combine family recipes, fresh ingredients, and a seamless ordering journey so every meal feels personal.
                </p>

                <div class="mt-8 space-y-4">
                    <div class="glass-panel p-5">
                        <div class="flex items-start gap-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-100 text-primary-500"><i class="fas fa-seedling"></i></div>
                            <div>
                                <h3 class="font-bold text-darkblue-700">Fresh and local</h3>
                                <p class="mt-1 text-sm leading-7 text-slate-600">We work with local produce and aromatic spices to keep every plate vibrant and authentic.</p>
                            </div>
                        </div>
                    </div>

                    <div class="glass-panel p-5">
                        <div class="flex items-start gap-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-100 text-primary-500"><i class="fas fa-mobile-alt"></i></div>
                            <div>
                                <h3 class="font-bold text-darkblue-700">Effortless ordering</h3>
                                <p class="mt-1 text-sm leading-7 text-slate-600">Browse dishes, manage your cart, and complete checkout with a polished, responsive flow.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card overflow-hidden">
                <img src="{{ asset('assets/about-us.jpg') }}" alt="Hot Dish kitchen" class="h-[420px] w-full object-cover">
            </div>
        </div>
    </div>
</div>
@endsection
