<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hot Dish - Authentic Sri Lankan Cuisine')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen flex flex-col antialiased">
    <nav class="sticky top-0 z-50 border-b border-white/10 bg-darkblue-600/95 backdrop-blur-xl">
        <div class="page-shell">
            <div class="flex h-16 items-center justify-between gap-4">
                <a href="/" class="flex items-center gap-3">
                    <img src="{{ asset('assets/logo.jpg') }}" alt="HOT DISH" class="h-10 w-10 rounded-full object-cover">
                    <span class="text-lg font-bold text-white md:text-xl">HOT DISH</span>
                </a>

                <div class="hidden items-center gap-8 md:flex">
                    <a href="/" class="text-sm font-semibold text-white/90 transition hover:text-primary-300">Home</a>
                    <a href="/menu" class="text-sm font-semibold text-white/90 transition hover:text-primary-300">Menu</a>
                    <a href="/about" class="text-sm font-semibold text-white/90 transition hover:text-primary-300">About</a>
                    <a href="/contact" class="text-sm font-semibold text-white/90 transition hover:text-primary-300">Contact</a>
                </div>

                <div class="flex items-center gap-3">
                    @auth
                        @livewire('cart-badge')
                        <a href="{{ route('profile.show') }}" class="rounded-full p-2 text-white transition hover:text-primary-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM6 20a7 7 0 0112 0" />
                            </svg>
                        </a>
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="rounded-full bg-white/10 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/20">Admin</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <button type="submit" class="rounded-full bg-primary-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-primary-500/30 transition hover:bg-primary-600">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-white/90 transition hover:text-primary-300">Login</a>
                        <a href="{{ route('register') }}" class="rounded-full bg-primary-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-primary-500/30 transition hover:bg-primary-600">Sign Up</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        @if(session('success') || session('error'))
            <div class="page-shell px-4 py-4">
                <div class="mx-auto max-w-3xl space-y-3">
                    @if(session('success'))
                        <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-700">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="rounded-3xl border border-rose-200 bg-rose-50 p-4 text-rose-700">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <footer class="mt-auto bg-darkblue-700 text-white">
        <div class="page-shell py-12">
            <div class="grid gap-10 md:grid-cols-4">
                <div>
                    <div class="mb-5 flex items-center gap-3">
                        <img src="{{ asset('assets/logo.jpg') }}" alt="HOT DISH" class="h-10 w-10 rounded-full object-cover">
                        <span class="text-lg font-bold">HOT DISH</span>
                    </div>
                    <p class="text-sm leading-7 text-slate-300">Authentic Sri Lankan flavors delivered to your doorstep with a modern ordering experience.</p>
                </div>
                <div>
                    <h4 class="mb-4 text-sm font-bold uppercase tracking-[0.3em] text-primary-300">Quick Links</h4>
                    <ul class="space-y-3 text-sm text-slate-200">
                        <li><a href="/" class="transition hover:text-primary-300">Home</a></li>
                        <li><a href="/menu" class="transition hover:text-primary-300">Menu</a></li>
                        <li><a href="/about" class="transition hover:text-primary-300">About</a></li>
                        <li><a href="/contact" class="transition hover:text-primary-300">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="mb-4 text-sm font-bold uppercase tracking-[0.3em] text-primary-300">Contact</h4>
                    <ul class="space-y-3 text-sm text-slate-200">
                        <li class="flex items-center gap-3"><span class="text-primary-300">☎</span> +94 11 234 5678</li>
                        <li class="flex items-center gap-3"><span class="text-primary-300">✉</span> info@hotdish.lk</li>
                        <li class="flex items-center gap-3"><span class="text-primary-300">📍</span> Colombo, Sri Lanka</li>
                    </ul>
                </div>
                <div>
                    <h4 class="mb-4 text-sm font-bold uppercase tracking-[0.3em] text-primary-300">Service</h4>
                    <p class="text-sm leading-7 text-slate-200">Open daily from 10:00 AM to 10:00 PM with fast delivery and kitchen-fresh meals.</p>
                </div>
            </div>
            <div class="mt-10 border-t border-white/10 pt-6 text-center text-sm text-slate-300">
                &copy; 2026 Hot Dish. All rights reserved.
            </div>
        </div>
    </footer>

    @livewireScripts
    @stack('scripts')
</body>
</html>
