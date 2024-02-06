<x-canvas-guest-layout>
    <x-auth-card>
        <x-slot name="text">
            <h1>@lang('auth.login')</h1>
            <p>@lang('auth.signInToYourAccount')</p>
            <a class="btn btn-success" href="{{ route('login') }}">
                {{ __('auth.login') }}
            </a>
        </x-slot>
        <div class="navbar-brand mb-4">
            <a href="{{ route('front.index') }}" aria-label="{{ config('app.name') }}">
                <x-application-logo />
            </a>
        </div>
        <form method="POST" class="w-100" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <div class="form-group mb-3">
                <x-text-input id="email" type="email" name="email" :value="old('email', $request->email)"
                    placeholder="{{ __('auth.email') }}" :error="$errors->has('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="form-group mb-3">
                <x-text-input id="password" type="password" name="password" placeholder="{{ __('auth.password') }}"
                    :error="$errors->has('password')" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="form-group mb-3">
                <x-text-input id="password_confirmation" type="password" name="password_confirmation"
                    placeholder="{{ __('auth.confirmPassword') }}" :error="$errors->has('password_confirmation')" required />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
            <div class="form-group d-grid">
                <x-primary-button>
                    {{ __('auth.resetPassword') }}
                </x-primary-button>
            </div>
        </form>
    </x-auth-card>
</x-canvas-guest-layout>
