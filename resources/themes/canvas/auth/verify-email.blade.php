<x-canvas-guest-layout>
    <div class="row">
        <div class="col-md-12">
            <div class="form-container">
                <p>{{ __('auth.verifyEmailFirst') }}</p>
                @if (session('status') == 'verification-link-sent')
                    <div class="mb-4 bold">
                        {{ __('auth.newCodeSent') }}
                    </div>
                @endif
                <div class="mt-4 d-flex gap-3">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <div class="d-grid">
                            <x-primary-button>
                                {{ __('auth.resendEmail') }}
                            </x-primary-button>
                        </div>
                    </form>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger">
                                {{ __('auth.signout') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-canvas-guest-layout>
