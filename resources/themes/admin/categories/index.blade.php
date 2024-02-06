<x-app-layout>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ !isset($category) ? __('common.createNew') : __('common.edit') }}</h6>
                </div>
                <form
                    action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $category->id ?? null }}">
                    <div class="card-body">
                        @if ($locales->count() > 1)
                            <ul class="nav nav-tabs" role="tablist">
                                @foreach ($locales as $index => $locale)
                                    <li class="nav-item">
                                        <a class="nav-link @if ($index == 0) active @endif"
                                            data-coreui-toggle="tab" href="#locale_{{ $locale->locale }}" role="tab"
                                            aria-controls="{{ $locale->name }}">
                                            <i class="icon-arrow-right"></i> {{ $locale->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        <div class="tab-content">
                            @foreach ($locales as $index => $locale)
                                @if (isset($category))
                                    @php($category_locale = $category->translate($locale->locale))
                                @endif
                                <div class="tab-pane @if ($index == 0) active @endif"
                                    id="locale_{{ $locale->locale }}" role="tabpanel">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">@lang('common.name')</label>
                                        <input
                                            class="form-control @error($locale->locale . '.name') is-invalid @enderror slug_title"
                                            id="{{ $locale->locale }}[name]" name="{{ $locale->locale }}[name]"
                                            value="{{ $category_locale->name ?? old($locale->locale . '.name') }}"
                                            type="text" placeholder="@lang('common.enterName')"
                                            @if ($index == 0) required autofocus @endif>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label col-md-3">@lang('common.slug')</label>
                                        <input
                                            class="form-control @error($locale->locale . '.slug') is-invalid @enderror slug"
                                            id="{{ $locale->locale }}[slug]" name="{{ $locale->locale }}[slug]"
                                            value="{{ $category_locale->slug ?? old($locale->locale . '.slug') }}"
                                            type="text" @if ($index == 0) required @endif>
                                        <span class="small text-muted">@lang('common.slugHelp')</span>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">@lang('common.title')</label>
                                        <input
                                            class="form-control @error($locale->locale . '.title') is-invalid @enderror"
                                            id="{{ $locale->locale }}[title]" name="{{ $locale->locale }}[title]"
                                            value="{{ $category_locale->title ?? old($locale->locale . '.title') }}"
                                            type="text">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">@lang('common.description')</label>
                                        <textarea class="form-control editor @error($locale->locale . '.description') is-invalid @enderror"
                                            id="{{ $locale->locale }}[description]" name="{{ $locale->locale }}[description]">{{ $category_locale->description ?? old($locale->locale . '.description') }}</textarea>
                                        <span class="small text-muted">@lang('common.descriptionHelp')</span>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">@lang('common.metaTitle')</label>
                                        <input
                                            class="form-control @error($locale->locale . '.meta_title') is-invalid @enderror"
                                            id="{{ $locale->locale }}[meta_title]"
                                            name="{{ $locale->locale }}[meta_title]"
                                            value="{{ $category_locale->meta_title ?? old($locale->locale . '.meta_title') }}"
                                            type="text">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">@lang('common.metaDescription')</label>
                                        <textarea class="form-control @error($locale->locale . '.meta_description') is-invalid @enderror"
                                            id="{{ $locale->locale }}[meta_description]" name="{{ $locale->locale }}[meta_description]">{{ $category_locale->meta_description ?? old($locale->locale . '.meta_description') }}</textarea>
                                        <span class="small text-muted">@lang('common.descriptionHelp')</span>
                                    </div>
                                </div>
                            @endforeach
                            <input type="hidden" name="type" value="{{ $category->type ?? $type }}">
                            @if ($isChildAllowed == 0)
                                <div class="form-group mb-3">
                                    <label for="parent" class="form-label">@lang('admin.parentCategory')</label>
                                    <select class="form-control" id="parent" name="parent">
                                        <option value="">@lang('common.selectOne')</option>
                                        @foreach ($parents as $cat)
                                            <option value="{{ $cat->id }}"
                                                @if (isset($category) && $category->parent == $cat->id) selected @endif>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">@lang('common.save')</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-8">
            <x-manage-filters :search="true" :search-route="route('admin.categories', ['type' => $category->type ?? $type])" />
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">@lang('admin.manageCategories')</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-responsive-sm table-quizier mb-0">
                        <thead>
                            <tr>
                                <th>@lang('common.name')</th>
                                <th>@lang('common.title')</th>
                                <th>@lang('common.count')</th>
                                <th width="150">@lang('common.action')</th>
                            </tr>
                        </thead>
                        <tbody id="sortable">
                            @forelse ($categories as $category)
                                <tr class="sortable-item cursor-pointer" data-id="{{ $category->id }}">
                                    <td class="sortable-handle">{{ $category->name }}</td>
                                    <td>{{ $category->meta_title }}</td>
                                    <td>{{ $category->type == 'post' ? $category->posts->count() : $category->tools->count() }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content start">
                                            <a href="{{ $category->type == 'post' ? route('blog.category', ['category' => $category->slug]) : route($category->type . '.category', ['category' => $category->slug]) }}"
                                                class="btn btn-link text-body" role="button" data-toggle="tooltip"
                                                data-original-title="@lang('common.view')" target="_blank"><span
                                                    class="lni lni-eye"></span></a>
                                            <a href="{{ route('admin.categories.edit', $category) }}"
                                                class="btn btn-link text-body" role="button" data-toggle="tooltip"
                                                data-original-title="@lang('common.edit')"><span
                                                    class="lni lni-pencil-alt"></span></a>
                                            <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                                method="POST" class="d-inline-block">
                                                @method('DELETE')
                                                @csrf<button class="btn btn-link text-danger warning-delete frm-submit"
                                                    role="button" data-bs-toggle="tooltip" data-placement="right"
                                                    title="@lang('common.delete')"><span
                                                        class="lni lni-trash"></span></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @if (!$category->children->isEmpty())
                                    @foreach ($category->children as $children)
                                        <tr>
                                            <td>— {{ $children->name }}</td>
                                            <td>{{ $children->description }}</td>
                                            <td>{{ $category->type == 'post' ? $children->posts_count : $children->tools_count }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content start">
                                                    <a href="{{ route('admin.categories.edit', $children) }}"
                                                        class="btn btn-link text-body" role="button"
                                                        data-toggle="tooltip"
                                                        data-original-title="@lang('common.edit')"><span
                                                            class="lni lni-pencil-alt"></span></a>
                                                    <form
                                                        action="{{ route('admin.categories.destroy', $children->id) }}"
                                                        method="POST" class="d-inline-block">
                                                        @method('DELETE')
                                                        @csrf<button
                                                            class="btn btn-link text-danger warning-delete frm-submit"
                                                            role="button" data-bs-toggle="tooltip"
                                                            data-placement="right" title="@lang('common.delete')"><span
                                                                class="lni lni-trash"></span></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @empty
                                <tr>
                                    <td class="text-center" colspan="22">@lang('common.noRecordsFund')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($categories->hasPages())
                    <div class="card-footer">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    @if ((isset($category->type) && $category->type == 'tool') || (isset($type) && $type == 'tool'))
        @section('footer_scripts')
            <script src="{{ asset('themes/admin/js/ckeditor/ckeditor.js') }}"></script>
            <script>
                const APP = function() {
                    const initClassicEditor = function() {
                            document.querySelectorAll('.editor').forEach(elem => {
                                ClassicEditor.create(elem, {
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

                        },
                        changeOrder = function() {
                            Sortable.create(document.getElementById('sortable'), {
                                handle: '.sortable-handle',
                                draggable: '.sortable-item',
                                dragoverBubble: true,
                                store: {
                                    set: function(sortable) {
                                        var order = sortable.toArray();
                                        axios.put('{{ route('admin.categories.sort') }}', {
                                            order: order
                                        })
                                    }
                                }
                            })
                        };
                    return {
                        init: function() {
                            initClassicEditor()
                            @if ($isChildAllowed)
                                changeOrder()
                            @endif
                        }
                    }
                }();

                document.addEventListener("DOMContentLoaded", function(event) {
                    APP.init();
                });
            </script>
        @endsection
    @endif
</x-app-layout>
