<x-canvas-error-page wrapClass="wrap503 bg-white error-page vh-100" :has-navbar="false">
    <div class="row">
        <div class="col-md-6 text-center">
            <img src="{{ url('/themes/canvas/images/error-503.svg') }}" alt="@lang('common.error500Title')">
        </div>
        <div class="col-md-6 d-flex align-items-center">
            <div class="contant-box">
                <h1>@lang('common.error503Title')</h1>
                <p>
                    {{ !empty($exception->getMessage()) ? $exception->getMessage() : __('common.error503Subtitle') }}
                </p>
            </div>
        </div>
    </div>
</x-canvas-error-page>
