@props([
    'route' => route('admin.pages.store'),
    'title' => __('admin.createPage'),
    'button_text' => __('common.save'),
    'page' => null,
    'locales',
])

<form action="{{ $route }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="published" value="1">
    <input type="hidden" name="id" value="{{ $page->id ?? null }}">
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
    <div class="tab-content">
        @foreach ($locales as $locale)
            @if (isset($page))
                @php($page_locale = $page->translate($locale->locale))
            @endif
            <div class="tab-pane @if ($loop->first) active @endif" id="locale_{{ $locale->locale }}">
                <div class="row">
                    <div class="col-md-9">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">{{ $title }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3 row">
                                    <div class="col-md-12">
                                        <input
                                            class="form-control @error($locale->locale . '.title') is-invalid @enderror slug_title"
                                            id="{{ $locale->locale }}-title" placeholder="@lang('common.title')"
                                            name="{{ $locale->locale }}[title]"
                                            value="{{ $page_locale->title ?? old($locale->locale . '.title') }}"
                                            type="text" @if ($loop->first) required autofocus @endif>
                                        @error($locale->locale . '.title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group mb-3 row">
                                    <div class="col-md-12">
                                        <input
                                            class="form-control @error($locale->locale . '.slug') is-invalid @enderror slug"
                                            id="{{ $locale->locale }}-slug" name="{{ $locale->locale }}[slug]"
                                            placeholder="@lang('common.slug')"
                                            value="{{ $page_locale->slug ?? old($locale->locale . '.slug') }}"
                                            type="text" @if ($loop->first) required @endif>
                                        @error($locale->locale . '.slug')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <span class="small text-muted">@lang('common.slugHelp')</span>
                                    </div>
                                </div>
                                <div class="form-group mb-3 row">
                                    <div class="col-md-12">
                                        <textarea id="{{ $locale->locale }}_editor" name="{{ $locale->locale }}[content]" class="editor">{!! $page_locale->content ?? old($locale->locale . '.content') !!}</textarea>
                                    </div>
                                </div>
                                <div class="form-group mb-3 row">
                                    <div class="col-md-12">
                                        <textarea class="form-control @error($locale->locale . '.excerpt') is-invalid @enderror"
                                            id="{{ $locale->locale }}-excerpt" placeholder="@lang('common.excerpt')" name="{{ $locale->locale }}[excerpt]">{{ $page_locale->excerpt ?? old($locale->locale . '.excerpt') }}</textarea>
                                        <span class="small text-muted">@lang('common.excerptHelp')</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn btn-primary" type="submit"> {{ $button_text }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">@lang('common.seoSettings')</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="{{ $locale->locale }}-meta_title">@lang('common.metaTitle')</label>
                                    <input
                                        class="form-control @error($locale->locale . '.meta_title') is-invalid @enderror"
                                        id="{{ $locale->locale }}-meta_title" name="{{ $locale->locale }}[meta_title]"
                                        value="{{ $page_locale->meta_title ?? old($locale->locale . '.meta_title') }}"
                                        type="text">
                                    <span class="small text-muted">@lang('common.metaTitleHelp')</span>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="{{ $locale->locale }}-meta_description">@lang('common.metaDescription')</label>
                                    <textarea class="form-control @error($locale->locale . '.meta_description') is-invalid @enderror"
                                        id="{{ $locale->locale }}-meta_description" name="{{ $locale->locale }}[meta_description]">{{ $page_locale->meta_description ?? old($locale->locale . '.meta_description') }}</textarea>
                                    <span class="small text-muted">@lang('common.metaDescriptionHelp')</span>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">@lang('common.ogSettings')</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="{{ $locale->locale }}-og_title">@lang('common.ogTitle')</label>
                                    <input
                                        class="form-control @error($locale->locale . '.og_title') is-invalid @enderror"
                                        id="{{ $locale->locale }}-og_title" name="{{ $locale->locale }}[og_title]"
                                        value="{{ $page_locale->og_title ?? old($locale->locale . '.og_title') }}"
                                        type="text">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="{{ $locale->locale }}-og_description">@lang('common.ogDescription')</label>
                                    <textarea class="form-control @error($locale->locale . '.og_description') is-invalid @enderror"
                                        id="{{ $locale->locale }}-og_description" name="{{ $locale->locale }}[og_description]">{{ $page_locale->og_description ?? old($locale->locale . '.og_description') }}</textarea>
                                    <span class="small text-muted">@lang('common.ogDescriptionHelp')</span>
                                </div>
                                @if (isset($page_locale) && $page_locale->og_image)
                                    <div class="form-group mb-2">
                                        <img src="{{ url($page_locale->og_image) }}" class="img-fluid rounded">
                                    </div>
                                @endif
                                <div class="form-group mb-3">
                                    <label for="{{ $locale->locale }}_og_image"
                                        class="form-col-form-label">@lang('common.image')</label>
                                    <div class="input-group">
                                        <input
                                            class="form-control @error($locale->locale . '.og_image') is-invalid @enderror filepicker"
                                            id="{{ $locale->locale }}_og_image"
                                            name="{{ $locale->locale }}[og_image]"
                                            value="{{ $page_locale->og_image ?? old($locale->locale . '.og_image') }}"
                                            type="file">
                                        <span class="small text-muted">@lang('common.ogImageHelp')</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
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
                    link: {
                        addTargetToExternalLinks: true,
                        decorators: [{
                                mode: 'manual',
                                label: '{{ __('admin.targetBlank') }}',
                                attributes: {
                                    target: '_blank',
                                }
                            },
                            {
                                mode: 'manual',
                                label: '{{ __('admin.externalLink') }}',
                                attributes: {
                                    rel: 'nofollow noopener noreferrer'
                                }
                            }
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
    </script>
@endsection
