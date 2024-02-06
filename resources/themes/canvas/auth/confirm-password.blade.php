<x-guest-layout>
    <div class="row">
        <div class="col-md-12">
            <div class="form-container">
                <p>{{ __('auth.confirm_password_desc') }}</p>
                <div class="navbar-brand mb-3">
                    <a href="{{ route('front.index') }}" aria-label="{{ config('app.name') }}">
                        <x-application-logo />
                    </a>
                </div>
                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf
                    <div class="mb-3">
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                            autocomplete="current-password" :error="$errors->has('password')" placeholder="{{ __('auth.password') }}" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    <div class="d-grid">
                        <x-primary-button>
                            {{ __('common.confirm') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
