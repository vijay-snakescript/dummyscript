@props([
    'route' => route('admin.posts.store'),
    'title' => __('admin.createPost'),
    'button_text' => __('common.save'),
    'post' => null,
    'locales',
    'users',
    'categories',
    'tags',
])

<form action="{{ $route }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $post->id ?? null }}">
    <input type="hidden" name="status" value="published">
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
        <div class="col">
            <div class="tab-content">
                @foreach ($locales as $locale)
                    @if (isset($post))
                        @php($post_locale = $post->translate($locale->locale))
                    @endif
                    <div class="tab-pane @if ($loop->first) active @endif"
                        id="locale_{{ $locale->locale }}">
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
                                            value="{{ $post_locale->title ?? old($locale->locale . '.title') }}"
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
                                            value="{{ $post_locale->slug ?? old($locale->locale . '.slug') }}"
                                            type="text" @if ($loop->first) required @endif>
                                        @error($locale->locale . '.slug')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <span class="small text-muted">@lang('common.slugHelp')</span>
                                    </div>
                                </div>
                                <div class="form-group mb-3 row">
                                    <div class="col-md-12">
                                        <textarea id="{{ $locale->locale }}_editor" name="{{ $locale->locale }}[contents]" class="editor">{!! $post_locale->contents ?? old($locale->locale . '.contents') !!}</textarea>
                                    </div>
                                </div>
                                <div class="form-group mb-3 row">
                                    <div class="col-md-12">
                                        <textarea class="form-control @error($locale->locale . '.excerpt') is-invalid @enderror"
                                            id="{{ $locale->locale }}-excerpt" placeholder="@lang('common.excerpt')" name="{{ $locale->locale }}[excerpt]">{{ $post_locale->excerpt ?? old($locale->locale . '.excerpt') }}</textarea>
                                        <span class="small text-muted">@lang('common.excerptHelp')</span>
                                    </div>
                                </div>
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
                                        value="{{ $post_locale->meta_title ?? old($locale->locale . '.meta_title') }}"
                                        type="text">
                                    <span class="small text-muted">@lang('common.metaTitleHelp')</span>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label"
                                        for="{{ $locale->locale }}-meta_description">@lang('common.metaDescription')</label>
                                    <textarea class="form-control @error($locale->locale . '.meta_description') is-invalid @enderror"
                                        id="{{ $locale->locale }}-meta_description" name="{{ $locale->locale }}[meta_description]">{{ $post_locale->meta_description ?? old($locale->locale . '.meta_description') }}</textarea>
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
                                        value="{{ $post_locale->og_title ?? old($locale->locale . '.og_title') }}"
                                        type="text">
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label"
                                        for="{{ $locale->locale }}-og_description">@lang('common.ogDescription')</label>
                                    <textarea class="form-control @error($locale->locale . '.og_description') is-invalid @enderror"
                                        id="{{ $locale->locale }}-og_description" name="{{ $locale->locale }}[og_description]">{{ $post_locale->og_description ?? old($locale->locale . '.og_description') }}</textarea>
                                    <span class="small text-muted">@lang('common.ogDescriptionHelp')</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="card mb-3">

                <div class="card-header">
                    <h6 class="mb-0">@lang('admin.author')</h6>
                </div>
                <div class="card-body">

                    <label class="form-label" for="author_id">@lang('admin.author')</label>
                    <div class="form-group">
                        <select class="form-control" id="author_id" name="author_id">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    @if ($post && $post->author_id == $user->id) selected @elseif($user->id == Auth::user()->id) selected @endif>
                                    {{ $user->name }}</option>
                            @endforeach
                        </select>
                        <span class="small text-muted">@lang('admin.authorHelp')</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="aside-lg">
            <div class="card mb-3">
                <div class="card-footer rounded-0 text-end">
                    <button class="btn btn-primary" type="submit"> {{ $button_text }}</button>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">@lang('admin.categories')</h6>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label class="form-label" for="author_id">@lang('admin.selectCategories')</label>
                        <select class="form-control" id="category" name="categories[]" multiple>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    @if (
                                        ($post && in_array($category->id, $post->categories->pluck('id')->toArray())) ||
                                            in_array($category->id, old('categories', []))) selected @endif>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">@lang('admin.tags')</h6>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <input type="text" class="form-control tagging" name="tags"
                            data-whitelisted="{{ json_encode($tags->toArray()) }}"
                            value="{{ $post ? $post->tags->pluck('name') : old('tags') }}">
                        <span class="small text-muted">@lang('admin.tagHelp')</span>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">@lang('common.featuredImage')</h6>
                </div>
                <div class="card-body">
                    @if ($post && $post->getFirstMediaUrl('featured-image'))
                        <div class="form-group mb-2">
                            <img src="{{ $post->getFirstMediaUrl('featured-image') }}" class="img-fluid rounded">
                        </div>
                    @endif
                    <div class="form-group">
                        <div class="input-group">
                            <input class="form-control @error('featured_image') is-invalid @enderror filepicker"
                                id="featured_image" name="featured_image" type="file">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@section('footer_scripts')
    <script src="{{ asset('themes/admin/js/ckeditor/ckeditor.js') }}"></script>
    <script>
        const APP = function() {
            const initClassicEditor = function() {
                document.querySelectorAll('.editor').forEach(elem => {
                    ClassicEditor.create(elem, {
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

            };
            return {
                init: function() {
                    initClassicEditor()
                }
            }
        }();

        document.addEventListener("DOMContentLoaded", function(event) {
            APP.init();
        });
    </script>
@endsection
