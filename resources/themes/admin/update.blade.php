<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="@lang('front.direction')">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ setting('app_name', 'MonsterTools') }} - Update Application</title>
    @vite(['resources/themes/admin/assets/sass/app.scss', 'resources/themes/admin/assets/js/app.js'])
    {!! Meta::toHtml() !!}
</head>

<body class="app vh-100 d-flex flex-row align-items-center">
    <div class="container-md rounded bg-white">
        <div class="row justify-content-center">
            <div class="col-md-12 text-center p-5">
                <form id="update-run" action="{{ route('system.update.run') }}" method="post">
                    @csrf
                    <h1>Update Application</h1>

                    <p>This might take several minutes, please don't close this browser tab while update is in progress.
                    </p>
                    <div class="center-buttons text-center">
                        <button class="btn text-white btn-danger btn-lg" type="submit">Update Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
