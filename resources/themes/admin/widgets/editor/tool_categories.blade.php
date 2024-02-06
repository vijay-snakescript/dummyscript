@extends('widgets.template')

@section('widget-form-' . $widget->id)
    <div class="form-group mb-3">
        <label class="form-label">@lang('widgets.showToolCounts')</label>
        <div>
            <label class="switch switch-label switch-primary switch-pill">
                <input class="switch-input @error('settings.tool_counts') is-invalid @enderror"
                    id="settings-post-counts-{{ $widget->id }}" name="settings[tool_counts]" value="1"
                    {{ isset($widget->settings->tool_counts) && $widget->settings->tool_counts == 1 ? 'checked' : '' }}
                    type="checkbox">
                <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
            </label>
        </div>
    </div>
    <div class="form-group mb-3">
        <label class="form-label">@lang('widgets.hideEmpty')</label>
        <div>
            <label class="switch switch-label switch-primary switch-pill">
                <input class="switch-input @error('settings.hide_empty_tools') is-invalid @enderror"
                    id="settings-hide-empty-{{ $widget->id }}" name="settings[hide_empty_tools]" value="1"
                    {{ isset($widget->settings->hide_empty_tools) && $widget->settings->hide_empty_tools == 1 ? 'checked' : '' }}
                    type="checkbox">
                <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
            </label>
        </div>
    </div>
@endsection
