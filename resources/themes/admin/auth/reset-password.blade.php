<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="{{ route('admin.dashboard') }}">
                <x-application-auth-logo />
            </a>
        </x-slot>
        <form method="POST" action="{{ route('admin.password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <div class="input-group mb-4"><span class="input-group-text">
                    <i class="lni lni-envelope"></i></span>
                <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email', $request->email)"
                    required autofocus />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <div class="input-group mb-4"><span class="input-group-text">
                    <i class="lni lni-lock-alt"></i></span>
                <x-text-input id="password" class="form-control" type="password" name="password" required
                    placeholder="Password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <div class="input-group mb-4"><span class="input-group-text">
                    <i class="lni lni-lock-alt"></i></span>
                <x-text-input id="password_confirmation" class="form-control" type="password"
                    name="password_confirmation" required placeholder="Confirm Password" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            <div class="row">
                <div class="col-12">
                    <x-primary-button class="btn btn-primary px-4">
                        {{ __('Reset Password') }}
                    </x-primary-button>
                </div>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
