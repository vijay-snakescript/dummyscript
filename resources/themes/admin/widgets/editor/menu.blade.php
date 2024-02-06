@extends('widgets.template')

@section('widget-form-' . $widget->id)
    <div class="form-group mb-3">
        <label for="widget-menu-{{ $widget->id }}" class="form-label">@lang('widgets.selectMenu')</label>
        <select class="form-select" name="settings[menu_id]" id="widget-menu-{{ $widget->id }}">
            <option value="">@lang('common.selectOne')</option>
            @foreach ($menus as $menu)
                <option value="{{ $menu->id }}" @if (isset($widget->settings->menu_id) && $menu->id == $widget->settings->menu_id) selected @endif>
                    {{ $menu->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group mb-3">
        <label for="widget-style-{{ $widget->id }}" class="form-label">@lang('widgets.menuStyle')</label>
        <select class="form-select" name="settings[menu_style]" id="widget-style-{{ $widget->id }}">
            <option value="">@lang('common.selectOne')</option>
            <option value="list"@if (isset($widget->settings->menu_style) && $menu->id == $widget->settings->menu_style) selected @endif>@lang('common.list')</option>
            <option value="inline"@if (isset($widget->settings->menu_style) && $menu->id == $widget->settings->menu_style) selected @endif>@lang('common.inline')</option>
        </select>
    </div>
@endsection
