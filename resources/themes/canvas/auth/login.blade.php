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
        <h1>@lang('auth.login')</h1>
        <x-application-social-auth />
        <p>@lang('auth.signInToYourAccount')</p>
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <form id="frm-login" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <x-text-input id="email" type="email" name="email" :value="old('email')" required
                    placeholder="{{ __('auth.email') }}" :error="$errors->has('email')" autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="mb-3">
                <x-text-input id="password" type="password" name="password" required :error="$errors->has('password')"
                    autocomplete="current-password" placeholder="{{ __('auth.password') }}" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            @if ($errors->has('captcha'))
                <div class="form-group mb-3">
                    <div class="is-invalid"></div>
                    <span class="invalid-feedback">
                        {{ $errors->first('captcha') }}
                    </span>
                </div>
            @endif
            <div class="d-flex justify-content-between mb-3">
                <label for="remember_me" class="d-inline-flex items-align-center">
                    <input id="remember_me" type="checkbox" class="form-checkbox" name="remember">
                    <span class="ms-2 small">{{ __('Remember me') }}</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="small" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>
            <div class="d-grid gap-2">
                @if (setting('recaptcha_status', 0) && setting('recaptcha_login', 0))
                    <x-primary-button class="g-recaptcha" data-sitekey="{{ setting('recaptcha_site') }}"
                        data-callback="onSubmit">
                        {{ __('auth.login') }}
                    </x-primary-button>
                @else
                    <x-primary-button class="btn btn-primary px-4">
                        {{ __('auth.login') }}
                    </x-primary-button>
                @endif
            </div>
        </form>
    </x-auth-card>
    @if (setting('recaptcha_status', 0) && setting('recaptcha_login', 0))
        @push('page_scripts')
            <script type="text/javascript" src="https://www.google.com/recaptcha/api.js?hl={{ app()->getLocale() }}" async defer>
            </script>
            <script type="text/javascript">
                function onSubmit(token) {
                    document.getElementById("frm-login").submit();
                }
            </script>
        @endpush
    @endif
</x-canvas-guest-layout>
