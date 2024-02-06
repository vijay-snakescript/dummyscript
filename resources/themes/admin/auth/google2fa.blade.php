<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <x-application-auth-logo />
        </x-slot>
        <x-auth-session-status class="mb-4" :status="session('status')" />
         <h1>@lang('auth.twoFactorAuthentication')</h1>
        <p>@lang('auth.twoFactorAuthenticationHelp')</p>
        <form method="POST" action="{{ route('admin.authenticate') }}">
            @csrf
            <div class="mb-3">
                <x-text-input id="one_time_password" type="text" :error="$errors->has('one_time_password')" name="one_time_password" required
                    placeholder="{{ __('auth.enterOTP') }}" />
                <x-input-error :messages="$errors->get('one_time_password')" class="mt-2" />
            </div>
            <div class="d-grid gap-2">
                <x-primary-button>
                    {{ __('auth.verify') }}
                </x-primary-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>

