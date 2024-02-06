@if (setting('update_available') == 1 && !session()->has('disable_updates'))
    <div class="alert alert-warning fade show" role="alert">
        {{ setting('update_available_msg') }}
        <a class="btn btn-sm btn-success text-white" href="{{ route('update.verifyUpdates') }}">
            <i class="fa fa-refresh"></i> @lang('admin.updateNow')
        </a>
    </div>
@endif
@php
    session()->forget('disable_updates');
@endphp
