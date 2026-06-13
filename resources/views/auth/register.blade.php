<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="space-y-1 text-center">
            <h1 class="text-2xl font-bold tracking-tight text-darkblue-700">Create your account</h1>
        </div>

        <x-validation-errors class="mb-4 mt-5 rounded-2xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm text-rose-700" />

        <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-semibold text-darkblue-700">Name</label>
                <x-input id="name" class="input-field mt-2" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-darkblue-700">Email</label>
                <x-input id="email" class="input-field mt-2" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-darkblue-700">Password</label>
                <x-input id="password" class="input-field mt-2" type="password" name="password" required autocomplete="new-password" minlength="8" />
                <p class="mt-2 text-xs text-slate-500">Minimum 8 characters; include letters and numbers for a stronger password.</p>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-darkblue-700">Confirm Password</label>
                <x-input id="password_confirmation" class="input-field mt-2" type="password" name="password_confirmation" required autocomplete="new-password" minlength="8" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="rounded-[24px] border border-slate-200 bg-slate-50 px-4 py-3">
                    <label for="terms" class="flex items-start gap-3 text-sm text-slate-600">
                        <x-checkbox name="terms" id="terms" required />
                        <span>
                            {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="font-semibold text-primary-600 hover:text-primary-700">'.__('Terms of Service').'</a>',
                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="font-semibold text-primary-600 hover:text-primary-700">'.__('Privacy Policy').'</a>',
                            ]) !!}
                        </span>
                    </label>
                </div>
            @endif

            <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
                <x-button class="btn-primary w-full sm:w-auto">
                    Register
                </x-button>

                <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 transition hover:text-darkblue-700">
                    Already registered?
                </a>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
