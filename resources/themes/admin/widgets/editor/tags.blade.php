@extends('widgets.template')

@section('widget-form-' . $widget->id)
    <div class="form-group mb-3">
        <label for="settings-limit-{{ $widget->id }}" class="form-label">@lang('widgets.noOfTags')</label>
        <input class="form-control" id="settings-limit-{{ $widget->id }}" name="settings[limit]"
            value="{{ $widget->settings->limit ?? 15 }}" type="number">
    </div>
    <div class="form-group mb-3">
        <label class="form-label">@lang('widgets.hideEmpty')</label>
        <div>
            <label class="switch switch-label switch-primary switch-pill">
                <input class="switch-input @error('settings.hide_empty') is-invalid @enderror"
                    id="settings-hide-empty-{{ $widget->id }}" name="settings[hide_empty]" value="1"
                    {{ isset($widget->settings->hide_empty) && $widget->settings->hide_empty == 1 ? 'checked' : '' }}
                    type="checkbox">
                <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
            </label>
        </div>
    </div>
@endsection
