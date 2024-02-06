<div class="toast-container position-fixed top-0 end-0 p-3">
    @if (count($errors) > 0)
        <div class="toast toast-danger show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
            <div class="d-flex">
                <div class="toast-body">
                    @if (count($errors) > 1)
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @else
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    @endif
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="@lang('common.close')"></button>
            </div>
        </div>
    @endif
    @if (session('success'))
        <div class="toast toast-success show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="@lang('common.close')"></button>
            </div>
        </div>
    @endif
    @if (session('error'))
        <div class="toast toast-danger show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="@lang('common.close')"></button>
            </div>
        </div>
    @endif
</div>
