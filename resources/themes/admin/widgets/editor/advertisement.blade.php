@extends('widgets.template')

@section('widget-form-' . $widget->id)
    <div class="form-group mb-3">
        <label for="name" class="text-md-right">@lang('widgets.advertisement.title')</label>
        <select class="form-select" id="settings-code-{{ $widget->id }}" name="settings[advertisement_id]">
            @foreach ($advertisements as $ads)
                <option value="{{ $ads->id }}" @if (isset($widget->settings->advertisement_id) && $widget->settings->advertisement_id == $ads->id) selected @endif>{{ $ads->name }}
                </option>
            @endforeach
        </select>
    </div>
@endsection
