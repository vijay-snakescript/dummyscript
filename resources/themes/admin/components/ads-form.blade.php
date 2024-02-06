@props(['type' => null, 'advertisement' => null])
<form
    action="{{ isset($advertisement) ? route('admin.advertisements.update', $advertisement) : route('admin.advertisements.store') }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $advertisement->id ?? null }}">
    <input type="hidden" name="type" value="{{ $type }}">
    <div class="col-md-12 mb-3">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">{{ isset($tag) ? __('common.edit') : __('common.createNew') }}</h6>
            </div>
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="name" class="form-label">@lang('common.name')</label>
                    <input class="form-control" id="name" name="name"
                        value="{{ $advertisement->name ?? old('name') }}" type="text" placeholder="@lang('common.name')"
                        required>
                </div>
                <div class="form-group mb-3">
                    <label for="title" class="form-label">@lang('common.title')</label>
                    <input class="form-control" id="title" name="title"
                        value="{{ $advertisement->title ?? old('title') }}" type="text"
                        placeholder="@lang('common.title')" required>
                </div>
                @if ($type == 1)
                    <div class="row">
                        <div class="form-group mb-3 col-md-12">
                            <label for="title" class="form-label">@lang('common.description')</label>
                            <textarea class="form-control" id="description" name="options[description]" value="" type="text"
                                placeholder="@lang('common.description')" required>{{ $advertisement->options['description'] ?? old('title') }}</textarea>
                        </div>
                        <div class="form-group mb-3 col-md-12">
                            <label for="target_url" class="form-label">@lang('admin.targetUrl')</label>
                            <input class="form-control" id="target_url" name="options[target_url]"
                                value="{{ $advertisement->options['target_url'] ?? old('target_url') }}" type="text"
                                placeholder="@lang('admin.targetUrl')" required>
                        </div>
                        @if (isset($advertisement) && isset($advertisement->options['image']))
                            <div class="form-group mb-2">
                                <img src="{{ url($advertisement->options['image']) }}" class="img-fluid rounded">
                            </div>
                        @endif
                        <div class="form-group mb-3 col-md-12">
                            <label for="image" class="form-label">@lang('admin.image')</label>
                            <input class="form-control filepicker" id="image" name="options[image]" type="file"
                                placeholder="@lang('admin.image')">
                        </div>
                    </div>
                @endif
                @if ($type == 2)
                    <div class="row">
                        <div class="form-group mb-3 col-md-12">
                            <label for="target_url" class="form-label">@lang('admin.targetUrl')</label>
                            <input class="form-control" id="target_url" name="options[target_url]"
                                value="{{ $advertisement->options['target_url'] ?? old('target_url') }}" type="text"
                                placeholder="@lang('admin.targetUrl')" required>
                        </div>
                        @if (isset($advertisement) && isset($advertisement->options['image']))
                            <div class="form-group mb-2">
                                <img src="{{ url($advertisement->options['image']) }}" class="rounded" height="150">
                            </div>
                        @endif
                        <div class="form-group mb-3 col-md-12">
                            <label for="image" class="form-label">@lang('admin.image')</label>
                            <input class="form-control filepicker" id="image" name="options[image]" type="file"
                                placeholder="@lang('admin.image')">
                        </div>
                    </div>
                @endif
                @if ($type == 3)
                    <div class="row">
                        <div class="form-group mb-3 col-md-12">
                            <label for="code" class="form-label">@lang('admin.code')</label>
                            <textarea class="form-control" id="code" name="options[code]" type="text" placeholder="@lang('common.code')"
                                required>{{ $advertisement->options['code'] ?? old('code') }}</textarea>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <button type="submit" class="btn btn-primary">@lang('common.save')</button>
    </div>
</form>
