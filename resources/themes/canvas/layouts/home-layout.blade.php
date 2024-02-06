<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="@lang('front.direction')"
    theme-mode="{{ (setting('dark_default_theme') == 'dark' && setting('enable_dark_mode') == 1 && request()->cookie('siteMode') != 'light') || request()->cookie('siteMode') === 'dark' ? 'dark' : 'light' }}">

<head>
    <meta name="app-search" content="{{ route('search') }}">
    @vite(['resources/themes/canvas/assets/sass/app.scss', 'resources/themes/canvas/assets/js/app.js'])
    @meta_tags()
    @meta_tags('header')
    @stack('page_header')
    @if (setting('enable_header_code', 0))
        {!! setting('header_code') !!}
    @endif
</head>

<body class="bg-white">
    <x-application-theme-switch />
    <main class="main-wrapper">
        <x-application-navbar />
        <x-application-breadcrumbs />
        {{ $slot }}
    </main>
    <x-application-footer />
    <x-application-signout />
    <x-application-messages />
    <x-application-loader />
    <x-application-cookies-consent />
    <x-application-back-to-top />
    @meta_tags('footer')
    @stack('page_scripts')
    @if (setting('enable_footer_code', 0))
        {!! setting('footer_code') !!}
    @endif
</body>

</html>
