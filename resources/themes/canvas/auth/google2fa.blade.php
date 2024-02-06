<x-canvas-guest-layout>
    <x-auth-card>
        <x-slot name="text">
            <h1>@lang('auth.welcome_back')</h1>
            <p>@lang('auth.welcome_back_desc')</p>
            <a href="{{ route('register') }}" class="btn btn-success">@lang('auth.register')</a>
        </x-slot>
        <div class="navbar-brand mb-3">
            <a href="{{ route('front.index') }}" aria-label="{{ config('app.name') }}">
                <x-application-logo />
            </a>
        </div>
        <h1>@lang('auth.twoFactorAuthentication')</h1>
        <p>@lang('auth.twoFactorAuthenticationHelp')</p>
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <form method="POST" action="{{ route('user.authenticate') }}">
            @csrf
            <div class="mb-3">
                <x-text-input id="one_time_password" type="text" name="one_time_password" required
                    placeholder="{{ __('auth.enterOTP') }}" :error="$errors->has('one_time_password')" />
                <x-input-error :messages="$errors->get('one_time_password')" class="mt-2" />
            </div>
            <div class="d-grid gap-2">
                <x-primary-button>
                    {{ __('auth.verify') }}
                </x-primary-button>
            </div>
        </form>
    </x-auth-card>
</x-canvas-guest-layout>
