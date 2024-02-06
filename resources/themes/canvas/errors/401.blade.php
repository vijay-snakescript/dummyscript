<x-canvas-error-page wrapClass="wrap401 bg-white error-page vh-100" :has-navbar="false">
    <div class="row">
        <div class="col-md-6 text-center">
            <img src="{{ url('/themes/canvas/images/error-401.svg') }}" alt="@lang('common.error401Title')">
        </div>
        <div class="col-md-6 d-flex align-items-center mt-sm-4">
            <div class="contant-box">
                <h1>@lang('common.error401Title')</h1>
                <p>
                    {{ !empty($exception->getMessage()) ? $exception->getMessage() : __('common.error401Subtitle') }}
                </p>
            </div>
        </div>
    </div>
</x-canvas-error-page>
