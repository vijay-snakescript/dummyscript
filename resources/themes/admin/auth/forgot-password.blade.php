<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="{{ route('admin.dashboard') }}">
                <x-application-auth-logo />
            </a>
        </x-slot>
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <form method="POST" action="{{ route('admin.password.email') }}">
            @csrf
            <p class="text-medium-emphasis">
                {{ __('Forgot Password ?No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </p>
            <div class="input-group mb-4">
                <span class="input-group-text">
                    <i class="lni lni-envelope"></i>
                </span>
                <x-text-input id="email" class="form-control" placeholder="mail@someone.com" type="email"
                    name="email" :value="old('email')" :error="$errors->has('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="row">
                <div class="col-12">
                    <x-primary-button class="btn btn-primary px-4">
                        {{ __('Email Password Reset Link') }}
                    </x-primary-button>
                </div>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
