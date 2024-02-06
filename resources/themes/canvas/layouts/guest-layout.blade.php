<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="@lang('front.direction')" theme-mode="{{ (setting('dark_default_theme') == 'dark' && setting('enable_dark_mode') == 1 && request()->cookie('siteMode') != 'light') || request()->cookie('siteMode') === 'dark' ? 'dark' : 'light' }}">

<head>
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    @vite(['resources/themes/canvas/assets/sass/app.scss', 'resources/themes/canvas/assets/js/app.js'])
    @meta_tags()
    @meta_tags('header')
    @if (setting('enable_header_code', 0))
        {!! setting('header_code') !!}
    @endif
</head>

<body class="auth-body">
    <x-application-theme-switch />
    <main class="main-wrapper">
        <div class="signin-container">
            <div class="signin-main">
                {{ $slot }}
            </div>
        </div>
    </main>
    <x-application-messages />
    @meta_tags('footer')
    @if (setting('enable_footer_code', 0))
        {!! setting('footer_code') !!}
    @endif
    @stack('page_scripts')
</body>

</html>
