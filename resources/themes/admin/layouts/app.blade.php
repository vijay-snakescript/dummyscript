<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>
        {{ ($breadcrumb = Breadcrumbs::current()) ? $breadcrumb->title . ' - ' . config('app.name', 'Laravel') : config('app.name', 'Laravel') }}
    </title>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
    @vite(['resources/themes/admin/assets/sass/app.scss', 'resources/themes/admin/assets/js/app.js'])
    {!! Meta::toHtml() !!}
</head>

<body>
    <x-application-sidebar />
    <div class="wrapper d-flex flex-column min-vh-100">
        <x-application-header />
        <div class="body flex-grow-1 px-3">
            <div class="{{ !empty($breadcrumb->container) ? $breadcrumb->container : 'container-lg' }}">
                <x-application-alerts />
                {{ $slot }}
            </div>
        </div>
        <x-application-footer />
    </div>
    <x-application-messages />
    <x-application-logout-form />
    @yield('footer_scripts')
    @stack('page_scripts')
</body>

</html>
