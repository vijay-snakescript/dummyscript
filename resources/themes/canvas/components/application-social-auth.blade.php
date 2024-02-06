@if (
    (!empty(config('services.google.client_id')) && !empty(config('services.google.client_secret'))) ||
        (!empty(config('services.facebook.client_id')) && !empty(config('services.facebook.client_secret'))))
    <div class="social-logins">
        @if (!empty(config('services.google.client_id')) && !empty(config('services.google.client_secret')))
            <a href="{{ route('social.login.redirect', ['google']) }}" rel="nofollow noopener noreferrer">
                <i class="an an-google"></i>
            </a>
        @endif
        @if (!empty(config('services.facebook.client_id')) && !empty(config('services.facebook.client_secret')))
            <a href="{{ route('social.login.redirect', ['facebook']) }}" rel="nofollow noopener noreferrer">
                <i class="an an-facebook"></i>
            </a>
        @endif
    </div>
@endif
