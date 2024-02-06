@extends('widgets.template')

@section('widget-form-' . $widget->id)
    <div class="form-group mb-3">
        <label for="settings-limit-{{ $widget->id }}" class="form-label">@lang('widgets.noOfPosts')</label>
        <input class="form-control" id="settings-limit-{{ $widget->id }}" name="settings[limit]"
            value="{{ $widget->settings->limit ?? 5 }}" type="number">
    </div>
    <div class="form-group mb-3">
        <label for="settings-featured-{{ $widget->id }}" class="form-label">@lang('widgets.featured')</label>
        <select class="form-control" id="settings-featured-{{ $widget->id }}" name="settings[featured]">
            <option value="">@lang('common.selectOne')</option>
            <option value="featured" @if (isset($widget->settings->featured) && $widget->settings->featured === 'featured') selected @endif>Featured Posts</option>
            <option value="editor" @if (isset($widget->settings->featured) && $widget->settings->featured === 'editor') selected @endif>Editor Choice</option>
        </select>
    </div>
    <div class="form-group mb-3">
        <label for="settings-order-{{ $widget->id }}" class="form-label">@lang('widgets.orderBy')</label>
        <select class="form-control" id="settings-order-{{ $widget->id }}" name="settings[order]">
            <option value="">@lang('common.selectOne')</option>
            <option value="latest" @if (isset($widget->settings->order) && $widget->settings->order === 'latest') selected @endif>Latest Posts</option>
            <option value="oldest" @if (isset($widget->settings->order) && $widget->settings->order === 'oldest') selected @endif>Oldest Posts</option>
            <option value="random" @if (isset($widget->settings->order) && $widget->settings->order === 'random') selected @endif>Random Posts</option>
            <option value="popular" @if (isset($widget->settings->order) && $widget->settings->order === 'popular') selected @endif>Popular Posts</option>
        </select>
    </div>
    <div class="form-group mb-3">
        <label for="settings-layout-{{ $widget->id }}" class="form-label">@lang('widgets.layout')</label>
        <select class="form-control" id="settings-layout-{{ $widget->id }}" name="settings[layout]">
            <option value="">@lang('common.selectOne')</option>
            <option value="default" @if (isset($widget->settings->order) && $widget->settings->layout === 'default') selected @endif>Default Layout</option>
            <option value="list" @if (isset($widget->settings->layout) && $widget->settings->layout === 'list') selected @endif>Posts List</option>
            <option value="card" @if (isset($widget->settings->layout) && $widget->settings->layout === 'card') selected @endif>Posts Card</option>
        </select>
    </div>
@endsection
