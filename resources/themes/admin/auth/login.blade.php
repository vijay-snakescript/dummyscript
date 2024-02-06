<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <x-application-auth-logo />
        </x-slot>
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <form method="POST" id="frm-login" action="{{ route('admin.login') }}">
            @csrf
            <p class="text-medium-emphasis">@lang('auth.signInToYourAccount')</p>
            <div class="input-group mb-3"><span class="input-group-text">
                    <i class="lni lni-envelope"></i>
                </span>
                <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')"
                    placeholder="mail@someone.com" :error="$errors->has('email')" autocomplete="email" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="input-group mb-3"><span class="input-group-text">
                    <i class="lni lni-lock-alt"></i></span>
                <x-text-input id="password" class="form-control" type="password" name="password" required
                    placeholder="password" :error="$errors->has('password')" autocomplete="current-password" />
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
            <div class="row">
                <div class="col-6">
                    @if (setting('recaptcha_status', 0) && setting('recaptcha_on_admin_login', 0))
                        <x-primary-button class="btn btn-primary px-4 g-recaptcha"
                            data-sitekey="{{ setting('recaptcha_site') }}" data-callback="onSubmit">
                            {{ __('auth.login') }}
                        </x-primary-button>
                    @else
                        <x-primary-button class="btn btn-primary px-4">
                            {{ __('auth.login') }}
                        </x-primary-button>
                    @endif
                </div>
                <div class="col-6 text-end">
                    @if (Route::has('password.request'))
                        <a class="btn btn-link px-0" href="{{ route('admin.password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>
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
</x-guest-layout>
