<x-app-layout>
    <x-manage-filters :button="__('common.createNew')" :route="route('admin.posts.create')" :search="true" :search-route="route('admin.posts')" />
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0">@lang('admin.managePost')</h6></div>
                <div class="card-body p-0">
                    <table class="table table-responsive-sm table-quizier mb-0">
                        <thead>
                            <tr>
                                <th></th>
                                <th>@lang('common.title')</th>
                                <th>@lang('admin.author')</th>
                                <th>@lang('admin.tags')</th>
                                <th>@lang('admin.categories')</th>
                                <th>@lang('common.status')</th>
                                <th>@lang('common.dateAdded')</th>
                                <th width="150">@lang('common.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($posts as $post)
                                <tr>
                                    <td>
                                        @if ($post->getFirstMediaUrl('featured-image'))
                                            <img src="{{ $post->getFirstMediaUrl('featured-image') }}"
                                                alt="{{ $post->title }}" class="img-fluid rounded" width="75">
                                        @endif
                                    </td>
                                    <td><strong>{{ $post->title }}</strong></td>
                                    <td>{{ $post->author->name }}</td>
                                    <td>
                                        @foreach ($post->tags as $tag)
                                            <span class="badge badge-pill bg-dark">{{ $tag->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($post->categories as $category)
                                            <span class="badge badge-pill bg-dark">{{ $category->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <span
                                            class="badge text-capitalize {{ $post->status == 'draft' ? 'bg-warning' : 'bg-success' }}">
                                            {{ $post->status }} </span>
                                        @if ($post->featured == 1)
                                            <span class="badge bg-primary">
                                                @lang('admin.featured') </span>
                                        @endif
                                        @if ($post->featured == 2)
                                            <span class="badge bg-primary ">
                                                @lang('admin.editorChoice') </span>
                                        @endif
                                    </td>
                                    <td>{{ $post->created_at->format(setting('datetime_format', 'F d, Y h:ia')) }}</td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content start">

                                            @if (!empty($post->slug))
                                                <a href="{{ route('posts.show', ['slug' => $post->slug]) }}"
                                                    target="_blank" class="btn btn-link text-body" role="button"
                                                    data-toggle="tooltip" data-placement="left"
                                                    title="@lang('common.view')"><span class="lni lni-eye"></span></a>
                                            @endif
                                            <a href="{{ route('admin.posts.edit', $post) }}"
                                                class="btn btn-link text-body" role="button" data-toggle="tooltip"
                                                data-original-title="@lang('common.edit')"><span
                                                    class="lni lni-pencil-alt"></span></a>
                                            <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST"
                                                class="d-inline-block">
                                                @method('DELETE')
                                                @csrf<button class="btn btn-link text-danger warning-delete frm-submit"
                                                    role="button" data-bs-toggle="tooltip" data-placement="right"
                                                    title="@lang('common.delete')"><span
                                                        class="lni lni-trash"></span></button>
                                            </form>
                                            <div class="dropdown">
                                                <i class="lni lni-more-alt " id="dropdownMenuButton" type="button" data-coreui-toggle="dropdown"
                                                    aria-expanded="false"></i>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.posts.featured', ['post' => $post->id, 'id' => '1']) }}">
                                                         <i class="lni lni-diamond-alt"></i>  {{ $post->featured == 1 ? __('admin.removeFeatured') : __('admin.featured') }}</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.posts.featured', ['post' => $post->id, 'id' => '2']) }}">
                                                          <i class="lni lni-write"></i>  {{ $post->featured == 2 ? __('admin.removeEditor') : __('admin.editorChoice') }}</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="22">@lang('common.noRecordsFund')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($posts->hasPages())
                    <div class="card-footer">
                        {{ $posts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
