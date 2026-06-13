<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Hot Dish Admin' }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-950 text-slate-100">
        <div class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(255,107,53,0.2),transparent_18%),radial-gradient(circle_at_bottom_right,_rgba(59,130,246,0.15),transparent_16%),linear-gradient(180deg,#020617_0%,#0f172a_45%,#111827_100%)]">
            <div class="mx-auto flex min-h-screen max-w-7xl flex-col gap-6 px-4 py-5 sm:px-6 lg:flex-row lg:px-6">
                <aside class="w-full shrink-0 rounded-[28px] border border-white/10 bg-slate-950/85 p-5 backdrop-blur lg:w-72">
                    <div class="flex items-center gap-3 border-b border-white/10 pb-5">
                        <img src="{{ asset('assets/logo.jpg') }}" alt="Hot Dish" class="h-12 w-12 rounded-full object-cover">
                        <div>
                            <p class="text-[10px] uppercase tracking-[0.35em] text-primary-200">Hot Dish</p>
                            <p class="text-lg font-bold text-white">Admin Control</p>
                            <p class="text-sm text-slate-300">Backend operations</p>
                        </div>
                    </div>

                    <div class="mt-5 rounded-[24px] border border-primary-400/30 bg-primary-500/10 px-4 py-3">
                        <p class="text-xs uppercase tracking-[0.3em] text-primary-100">Current role</p>
                        <p class="mt-2 text-base font-bold text-white">{{ auth()->user()->name }}</p>
                        <p class="mt-1 text-sm text-slate-200">{{ auth()->user()->email }}</p>
                    </div>

                    <nav class="mt-5 space-y-2">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-primary-500/20 text-white' : 'text-slate-200 hover:bg-white/10' }}">
                            <span class="h-2.5 w-2.5 rounded-full bg-primary-400"></span>
                            Overview
                        </a>
                        <a href="{{ route('admin.menu') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition {{ request()->routeIs('admin.menu') ? 'bg-primary-500/20 text-white' : 'text-slate-200 hover:bg-white/10' }}">
                            <span class="h-2.5 w-2.5 rounded-full bg-orange-300"></span>
                            Menu Items
                        </a>
                        <a href="{{ route('admin.customers') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition {{ request()->routeIs('admin.customers') ? 'bg-primary-500/20 text-white' : 'text-slate-200 hover:bg-white/10' }}">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-300"></span>
                            Customers
                        </a>
                        <a href="{{ route('admin.orders') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition {{ request()->routeIs('admin.orders') ? 'bg-primary-500/20 text-white' : 'text-slate-200 hover:bg-white/10' }}">
                            <span class="h-2.5 w-2.5 rounded-full bg-sky-300"></span>
                            Orders
                        </a>
                    </nav>

                    <div class="mt-6 rounded-[24px] border border-white/10 bg-slate-900/80 p-4">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-300">Quick action</p>
                        <p class="mt-3 text-sm leading-6 text-slate-200">Keep the customer experience polished while managing menu, orders, and customer access from one secure control panel.</p>
                        <form method="POST" action="{{ route('logout') }}" class="mt-4">
                            @csrf
                            <button type="submit" class="btn-outline w-full">Logout</button>
                        </form>
                    </div>
                </aside>

                <div class="flex-1">
                    <div class="rounded-[28px] border border-white/10 bg-slate-950/80 px-5 py-4 backdrop-blur sm:px-6">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <p class="text-[10px] uppercase tracking-[0.35em] text-primary-200">Hot Dish admin</p>
                                <h1 class="mt-2 text-2xl font-black text-white sm:text-[1.7rem]">{{ $title ?? 'Operations dashboard' }}</h1>
                                <p class="mt-2 text-sm text-slate-200">Only administrators can access this backend area. Customer users are redirected away automatically.</p>
                            </div>
                            <div class="flex flex-wrap items-center gap-3 text-sm text-slate-100">
                                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1">{{ now()->format('M d, Y') }}</span>
                                <span class="rounded-full border border-primary-400/40 bg-primary-500/10 px-3 py-1 text-primary-100">{{ auth()->user()->role }}</span>
                            </div>
                        </div>
                    </div>

                    <main class="pt-6">
                        @yield('content')
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
