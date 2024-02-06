@extends('widgets.template')

@section('widget-form-' . $widget->id)
    <div class="form-group mb-3">
        <label for="name" class="text-md-right">@lang('widgets.textTitle')</label>
        <textarea class="form-control" id="settings-text-{{ $widget->id }}" name="settings[text]">{{ $widget->settings->text ?? '' }}</textarea>
    </div>
@endsection
