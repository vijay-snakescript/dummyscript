@if (!isset($innerLoop))
    <ul class="artisan-nav nav nav-menu ms-auto">
    @else
        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
@endif

@foreach ($items as $item)
    @php
        $originalItem = $item;

        $listItemClass = null;
        $linkAttributes = null;
        $styles = null;
        $icon = null;

        // Background Color or Color
        if (isset($options->color) && $options->color == true) {
            $styles = 'color:' . $item->color;
        }
        if (isset($options->background) && $options->background == true) {
            $styles = 'background-color:' . $item->color;
        }

        // With child Attributes
        if (isset($originalItem->child) && !$originalItem->child->isEmpty()) {
            if ($item->active) {
                $linkAttributes = 'class="nav-link dropdown-toggle active" data-bs-toggle="dropdown"';
            } else {
                $linkAttributes = 'class="nav-link dropdown-toggle" data-bs-toggle="dropdown"';
            }
            $listItemClass = 'nav-item dropdown';
        } else {
            $listItemClass = 'nav-item';

            if ($item->active) {
                $linkAttributes = 'class="nav-link active"';
            } else {
                $linkAttributes = 'class="nav-link"';
            }

            if (isset($innerLoop)) {
                $listItemClass = '';
                if ($item->active) {
                    $linkAttributes = 'class="dropdown-item active"';
                } else {
                    $linkAttributes = 'class="dropdown-item"';
                }
            }
        }

        // Set Icon
        if (isset($options->icon) && $options->icon == true && !empty($item->icon_class)) {
            if (Str::contains($item->icon_class, '<svg')) {
                $icon = "{$item->icon_class}";
            } else {
                $icon = '<i class="' . $item->icon_class . '"></i>';
            }
        }
    @endphp
    <li class="{{ $listItemClass }}">
        <a href="{{ $item->href }}" target="{{ $item->target }}" style="{{ $styles }}" {!! $linkAttributes ?? '' !!}>
            {!! $icon !!}
            {{ $item->label }}
        </a>
        @if (isset($originalItem->child) && !$originalItem->child->isEmpty())
            @include('menu.bootstrap', [
                'items' => $originalItem->child,
                'options' => $options,
                'innerLoop' => true,
            ])
        @endif
    </li>
@endforeach
@guest
    @if (Route::has('login') && !isset($innerLoop) && setting('disable_auth', 0) == 0)
        <li class="nav-item nav-btn">
            <a href="{{ route('login') }}" class="login-modal btn btn-primary rounded-pill">@lang('auth.login')</a>
        </li>
        @if (Route::has('register'))
            <li class="nav-item nav-btn">
                <a href="{{ route('register') }}" class="btn btn-outline-primary rounded-pill">@lang('auth.register')</a>
            </li>
        @endif
    @endif
@endguest
@auth
    @if (!isset($innerLoop))
        @if (setting('disable_auth', 0) == 0)
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">{{ auth()->user()->name }}</a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="nav-item"><a class="nav-link" role="button"
                            href="{{ route('user.profile') }}">{{ __('profile.profile') }}</a></li>
                    <li class="nav-item"><a class="nav-link" role="button"
                            href="{{ route('user.password') }}">{{ __('common.changePassword') }}</a></li>
                    <li class="nav-item"><a class="nav-link signoutBtn" role="button">{{ __('auth.signout') }}</a></li>
                </ul>
            </li>
        @endif
        @if (setting('disable_favorite_tools', '1') == 1)
            <x-favorite-navbar />
        @endif
    @endif
@endauth
</ul>
