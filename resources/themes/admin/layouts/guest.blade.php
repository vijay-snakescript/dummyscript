<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Monster Tools') }}</title>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    @vite(['resources/themes/admin/assets/sass/app.scss', 'resources/themes/admin/assets/js/app.js'])
    {!! Meta::toHtml() !!}
</head>

<body class="layout-guest">
    <div class="bg-light min-vh-100 d-flex flex-row align-items-center">
        <div class="container">
            {{ $slot }}
        </div>
    </div>
    <x-application-messages />
    @stack('page_scripts')
</body>

</html>
