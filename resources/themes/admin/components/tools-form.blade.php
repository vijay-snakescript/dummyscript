@props([
    'route' => route('admin.tools.edit', $tool),
    'title' => __('admin.editTool'),
    'button_text' => __('common.save'),
    'tool' => null,
    'locales',
    'categories',
    'form_fields' => [],
    'properties' => null,
])

<form action="{{ $route }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $tool->id ?? null }}">
    <div class="row">
        <div class="col-md-12">
            @if ($locales->count() !== 1)
                <ul class="nav nav-tabs mb-3" role="tablist">
                    @foreach ($locales as $locale)
                        <li class="nav-item">
                            <a class="nav-link @if ($loop->first) active @endif" data-coreui-toggle="tab"
                                href="#locale_{{ $locale->locale }}" role="tab" aria-controls="{{ $locale->name }}">
                                <i class="icon-arrow-right"></i> {{ $locale->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-9">
            <div class="tab-content">
                @foreach ($locales as $locale)
                    @if (isset($tool))
                        @php($tool_locale = $tool->translate($locale->locale))
                    @endif
                    <div class="tab-pane @if ($loop->first) active @endif"
                        id="locale_{{ $locale->locale }}">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">{{ $title }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3 row">
                                    <div class="col-md-12">
                                        <input
                                            class="form-control @error($locale->locale . '.name') is-invalid @enderror name slug_title copy-to-field"
                                            id="{{ $locale->locale }}-name" placeholder="@lang('admin.toolName')"
                                            name="{{ $locale->locale }}[name]"
                                            value="{{ $tool_locale->name ?? old($locale->locale . '.name') }}"
                                            type="text" @if ($loop->first) required autofocus @endif
                                            data-copy-elements="#en-meta_title,#en-og_title">
                                        @error($locale->locale . '.name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <span class="small text-muted">@lang('admin.toolNameHelp')</span>
                                    </div>
                                </div>
                                @if ($loop->first)
                                    <div class="form-group mb-3 row">
                                        <div class="col-md-12">
                                            <input class="form-control @error($tool->slug) is-invalid @enderror slug"
                                                id="slug" placeholder="@lang('common.slug')" name="slug"
                                                value="{{ $tool->slug ?? old($tool->slug) }}" type="text"
                                                @if ($loop->first) required autofocus @endif>
                                            @error($tool->slug)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <span class="small text-muted">@lang('common.slugHelp')</span>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group mb-3 row">
                                    <div class="col-md-12">
                                        <textarea class="form-control @error($locale->locale . '.content') is-invalid @enderror"
                                            id="{{ $locale->locale }}-content" placeholder="@lang('admin.toolDescription')" name="{{ $locale->locale }}[description]">{{ $tool_locale->description ?? old($locale->locale . '.description') }}</textarea>
                                        <span class="small text-muted">@lang('admin.toolDescriptionHelp')</span>
                                    </div>
                                </div>
                                <div class="form-group mb-3 row">
                                    <div class="col-md-12">
                                        <textarea id="{{ $locale->locale }}_editor" name="{{ $locale->locale }}[content]" class="editor">{!! $tool_locale->content ?? old($locale->locale . '.content') !!}</textarea>
                                    </div>
                                </div>
                                @if (isset($tool) && $tool->icon)
                                    <div class="form-group mb-2">
                                        <img src="{{ url($tool->icon) }}" class="img-fluid rounded">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">@lang('common.seoSettings')</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="form-label"
                                        for="{{ $locale->locale }}-meta_title">@lang('common.metaTitle')</label>
                                    <input
                                        class="form-control @error($locale->locale . '.meta_title') is-invalid @enderror"
                                        id="{{ $locale->locale }}-meta_title" name="{{ $locale->locale }}[meta_title]"
                                        value="{{ $tool_locale->meta_title ?? old($locale->locale . '.meta_title') }}"
                                        type="text">
                                    <span class="small text-muted">@lang('common.metaTitleHelp')</span>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label"
                                        for="{{ $locale->locale }}-meta_description">@lang('common.metaDescription')</label>
                                    <textarea class="form-control @error($locale->locale . '.meta_description') is-invalid @enderror"
                                        id="{{ $locale->locale }}-meta_description" name="{{ $locale->locale }}[meta_description]">{{ $tool_locale->meta_description ?? old($locale->locale . '.meta_description') }}</textarea>
                                    <span class="small text-muted">@lang('common.metaDescriptionHelp')</span>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">@lang('common.ogSettings')</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="form-label"
                                        for="{{ $locale->locale }}-og_title">@lang('common.ogTitle')</label>
                                    <input
                                        class="form-control @error($locale->locale . '.og_title') is-invalid @enderror"
                                        id="{{ $locale->locale }}-og_title" name="{{ $locale->locale }}[og_title]"
                                        value="{{ $tool_locale->og_title ?? old($locale->locale . '.og_title') }}"
                                        type="text">
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label"
                                        for="{{ $locale->locale }}-og_description">@lang('common.ogDescription')</label>
                                    <textarea class="form-control @error($locale->locale . '.og_description') is-invalid @enderror"
                                        id="{{ $locale->locale }}-og_description" name="{{ $locale->locale }}[og_description]">{{ $tool_locale->og_description ?? old($locale->locale . '.og_description') }}</textarea>
                                    <span class="small text-muted">@lang('common.ogDescriptionHelp')</span>
                                </div>
                                @if (isset($tool_locale) && $tool_locale->og_image)
                                    <div class="form-group mb-2">
                                        <img src="{{ url($tool_locale->og_image) }}" class="img-fluid rounded">
                                    </div>
                                @endif
                                <div class="form-group mb-3">
                                    <label for="{{ $locale->locale }}_og_image"
                                        class="form-label">@lang('common.image')</label>
                                    <div class="input-group">
                                        <input
                                            class="form-control @error($locale->locale . '.og_image') is-invalid @enderror filepicker"
                                            id="{{ $locale->locale }}_og_image"
                                            name="{{ $locale->locale }}[og_image]"
                                            value="{{ $tool_locale->og_image ?? old($locale->locale . '.og_image') }}"
                                            type="file">
                                        <span class="small text-muted">@lang('common.ogImageHelp')</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($tool && class_exists($tool->class_name) && method_exists($tool->class_name, 'index'))
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">@lang('tools.indexPageContent')</h6>
                                </div>
                                <div class="card-body" data-conditional-name="is_home" data-conditional-value="1">
                                    <div class="form-group mb-3 row">
                                        <div class="col-md-12">
                                            <textarea class="form-control editor @error($locale->locale . '.index_content') is-invalid @enderror"
                                                id="{{ $locale->locale }}-index_content" placeholder="@lang('admin.toolIndexContent')"
                                                name="{{ $locale->locale }}[index_content]">{{ $tool_locale->index_content ?? old($locale->locale . '.index_content') }}</textarea>
                                            <span class="small text-muted">@lang('admin.toolIndexContentHelp')</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">@lang('admin.settings')</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <div class="col-md-12">
                                    <x-input-label for="order">@lang('common.order')</x-input-label>
                                    <x-text-input id="order" :error="$errors->has('display')" name="display" type="number"
                                        value="{{ $tool->display }}">
                                    </x-text-input>
                                    <x-input-error :messages="$errors->get('display')" class="mt-2" />
                                </div>
                            </div>
                            <div class="form-group">
                                <x-input-label for="category">@lang('admin.category')</x-input-label>
                                <select id="category"
                                    class="form-control{{ $errors->has('category') ? ' is-invalid' : '' }}"
                                    id="category" name="category">
                                    <option value="">@lang('common.selectOne')</option>
                                    @foreach ($categories as $category)
                                        <option
                                            value="{{ $category->id }}"{{ in_array($category->id, $tool->category->pluck('id')->toArray()) ? ' selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('category')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button class="btn btn-primary text-white btn-sm" type="submit"> {{ $button_text }}</button>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0"> @lang('common.icon')</h6>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="form-label">@lang('admin.iconType')</label>
                            <select class="form-control" name="icon_type" id="icon_type">
                                <option value="file" @if ($tool->icon_type == 'file') selected @endif>
                                    @lang('admin.file')</option>
                                <option value="class" @if ($tool->icon_type == 'class') selected @endif>
                                    @lang('admin.class')</option>
                            </select>
                        </div>
                        <span class="small text-muted">@lang('admin.toolIconTypeHelp')</span>
                    </div>
                    <div class="form-group row mt-2">
                        <div class="input-group" data-conditional-name="icon_type" data-conditional-value="file">
                            <input class="form-control @error($tool->icon) is-invalid @enderror filepicker"
                                id="icon" name="icon" value="{{ $tool->icon }}" type="file">
                        </div>
                        <span class="small text-muted">@lang('admin.toolIconHelp')</span>
                    </div>
                    <div class="form-group mb-3">
                        <div class="col-md-12" data-conditional-name="icon_type" data-conditional-value="class">
                            <label class="form-label">@lang('admin.iconClassName')</label>
                            <input class="form-control" id="class-list-name" name="icon_class" type="text"
                                value="{{ $tool->icon_class }}">
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <div class="icons-wrap" data-conditional-name="icon_type" data-conditional-value="class">
                            <ul id="icons" class="icons-list">
                                @foreach (icons_class_lists() as $icons)
                                    <li data-toggle="tooltip" title="{{ $icons }}">
                                        <button data-type="{{ $icons }}" type="button"
                                            class="tool-icon-select" onclick="getClassListAttr(this)">
                                            <i class="an-duotone an-{{ $icons }}"></i>
                                            <span>{{ $icons }}</span>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @if (!empty($tool->properties))
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">@lang('tools.properties')</h6>
                    </div>
                    <div class="card-body">
                        @foreach ($properties->whereIn('prop_key', $tool->properties['properties'] ?? [0]) as $property)
                            <x-dynamic-component :component="$property->field_type . '-tool'" :property="$property" :tool="$tool"
                                :plan="isset($plan) ? $plan : null" />
                        @endforeach
                    </div>
                </div>
            @endif
            @if (isset($form_fields['fields']) && count($form_fields['fields']) > 0)
                <div class="card mb-3 sticky-top sticky-header-offset">
                    <div class="card-header">
                        <h6 class="mb-0">{{ $form_fields['title'] }}</h6>
                    </div>
                    <div class="card-body">
                        @foreach ($form_fields['fields'] as $field)
                            <x-dynamic-component :component="$field['field']" :field="$field" :tool="$tool">
                            </x-dynamic-component>
                        @endforeach
                    </div>
                </div>
            @endif
            @if ($tool && class_exists($tool->class_name) && method_exists($tool->class_name, 'index'))
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">@lang('tools.indexPage')</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label class="form-label" for="is_home">@lang('tools.markAsIndex')</label>
                            <select id="is_home" name="is_home" class="form-select">
                                <option value="0" @if (!$tool->is_home) selected @endif>No</option>
                                <option value="1" @if ($tool->is_home) selected @endif>Yes</option>
                            </select>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</form>

@section('footer_scripts')
    <script src="{{ asset('themes/admin/js/ckeditor/ckeditor.js') }}"></script>
    <script>
        document.querySelectorAll('.editor').forEach(elem => {
            ClassicEditor.create(elem, {
                    heading: {
                        options: [{
                                model: 'paragraph',
                                title: 'Paragraph',
                                class: 'ck-heading_paragraph'
                            },
                            {
                                model: 'heading1',
                                view: 'h1',
                                title: 'Heading 1',
                                class: 'ck-heading_heading1'
                            },
                            {
                                model: 'heading2',
                                view: 'h2',
                                title: 'Heading 2',
                                class: 'ck-heading_heading2'
                            },
                            {
                                model: 'heading3',
                                view: 'h3',
                                title: 'Heading 3',
                                class: 'ck-heading_heading3'
                            },
                        ]
                    },
                    simpleUpload: {
                        uploadUrl: '{{ route('uploader.upload') }}',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        }
                    },
                })
                .then(editor => {})
                .catch(error => {
                    console.log('error', error);
                });
        });

        function getClassListAttr(elem) {
            document.getElementById('class-list-name').value = elem.dataset.type;
        }
    </script>
@endsection
