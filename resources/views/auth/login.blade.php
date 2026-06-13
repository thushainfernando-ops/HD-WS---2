<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="space-y-1 text-center">
            <h1 class="text-2xl font-bold tracking-tight text-darkblue-700">Welcome back</h1>
        </div>

        <x-validation-errors class="mb-4 mt-5 rounded-2xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm text-rose-700" />

        @session('status')
            <div class="mb-4 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-semibold text-darkblue-700">Email</label>
                <x-input id="email" class="input-field mt-2" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-darkblue-700">Password</label>
                <x-input id="password" class="input-field mt-2" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3">
                <label for="remember_me" class="flex items-center gap-3 text-sm text-slate-600">
                    <x-checkbox id="remember_me" name="remember" />
                    <span>Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm font-semibold text-primary-600 transition hover:text-primary-700" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>

            <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
                <x-button class="btn-primary w-full sm:w-auto">
                    Log in
                </x-button>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-sm font-semibold text-slate-600 transition hover:text-darkblue-700">
                        Create an account
                    </a>
                @endif
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
