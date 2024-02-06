<x-canvas-error-page wrapClass="wrap404 error-page">
    <div class="row">
        <div class="col-md-6">
            <div class="ghost-box">
                <div class="box-ghost">
                    <div class="symbol"></div>
                    <div class="symbol"></div>
                    <div class="symbol"></div>
                    <div class="symbol"></div>
                    <div class="symbol"></div>
                    <div class="symbol"></div>
                    <div class="box-ghost-container">
                        <div class="box-ghost-eyes">
                            <div class="box__eye-left"></div>
                            <div class="box__eye-right"></div>
                        </div>
                        <div class="box-ghost-bottom">
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                    </div>
                    <div class="box-ghost-shadow"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="contant-box">
                <h1>@lang('common.error404Title')</h1>
                <p>
                    {{ !empty($exception->getMessage()) ? $exception->getMessage() : __('common.error404Subtitle') }}
                </p>
                <div class="buttons-col">
                    <div class="action-link-wrap">
                        <a href="{{ route('front.index') }}" class="btn btn-primary">@lang('common.goBackToHome')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-canvas-error-page>
