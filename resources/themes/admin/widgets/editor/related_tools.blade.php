@extends('widgets.template')

@section('widget-form-' . $widget->id)
    <div class="d-flex">
        <label for="name" class="form-label">@lang('admin.limit')</label>
        <input class="form-control" id="settings-limit-{{ $widget->id }}" name="settings[limit]"
            value="{{ $widget->settings->limit ?? 10 }}" type="number">
    </div>
@endsection
