@extends('widgets.template')

@section('widget-form-' . $widget->id)
    <div class="form-group mb-3">
        <label for="name" class="text-md-right">@lang('widgets.codeTitle')</label>
        <textarea class="form-control" id="settings-code-{{ $widget->id }}" name="settings[code]">{{ $widget->settings->code ?? '' }}</textarea>
    </div>
@endsection
