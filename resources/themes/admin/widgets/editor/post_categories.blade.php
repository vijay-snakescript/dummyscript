@extends('widgets.template')

@section('widget-form-' . $widget->id)
    <div class="form-group mb-3">
        <label class="form-label">@lang('widgets.showHierarchy')</label>
        <div>
            <label class="switch switch-label switch-primary switch-pill">
                <input class="switch-input @error('settings.hierarchy') is-invalid @enderror"
                    id="settings-hierarchy-{{ $widget->id }}" name="settings[hierarchy]" value="1"
                    {{ isset($widget->settings->hierarchy) && $widget->settings->hierarchy == 1 ? 'checked' : '' }}
                    type="checkbox">
                <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
            </label>
        </div>
    </div>
    <div class="form-group mb-3">
        <label class="form-label">@lang('widgets.displayList')</label>
        <div>
            <label class="switch switch-label switch-primary switch-pill">
                <input class="switch-input @error('settings.style') is-invalid @enderror"
                    id="settings-style-{{ $widget->id }}" name="settings[style]" value="1"
                    {{ isset($widget->settings->style) && $widget->settings->style == 1 ? 'checked' : '' }} type="checkbox">
                <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
            </label>
        </div>
    </div>
    <div class="form-group mb-3">
        <label class="form-label">@lang('widgets.showPostCounts')</label>
        <div>
            <label class="switch switch-label switch-primary switch-pill">
                <input class="switch-input @error('settings.post_counts') is-invalid @enderror"
                    id="settings-post-counts-{{ $widget->id }}" name="settings[post_counts]" value="1"
                    {{ isset($widget->settings->post_counts) && $widget->settings->post_counts == 1 ? 'checked' : '' }}
                    type="checkbox">
                <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
            </label>
        </div>
    </div>
    <div class="form-group mb-3">
        <label class="form-label">@lang('widgets.hideEmpty')</label>
        <div>
            <label class="switch switch-label switch-primary switch-pill">
                <input class="switch-input @error('settings.hide_empty_posts') is-invalid @enderror"
                    id="settings-hide-empty-{{ $widget->id }}" name="settings[hide_empty_posts]" value="1"
                    {{ isset($widget->settings->hide_empty_posts) && $widget->settings->hide_empty_posts == 1 ? 'checked' : '' }}
                    type="checkbox">
                <span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>
            </label>
        </div>
    </div>
@endsection
