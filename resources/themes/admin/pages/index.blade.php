<x-app-layout>
    <x-manage-filters :button="__('common.createNew')" :route="route('admin.pages.create')" :search="true" :search-route="route('admin.pages')" />
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0">@lang('admin.managePages')</h6></div>
                <div class="card-body p-0">
                    <table class="table table-responsive-sm table-quizier mb-0">
                        <thead>
                            <tr>
                                <th>@lang('common.image')</th>
                                <th>@lang('common.title')</th>
                                <th>@lang('common.status')</th>
                                <th>@lang('common.dateAdded')</th>
                                <th width="150">@lang('common.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pages as $page)
                                <tr>
                                    <td>
                                        @if (!empty($page->og_image))
                                            <img src="{{ url($page->og_image) }}" class="rounded" width="50">
                                        @endif
                                    </td>
                                    <td><strong>{{ $page->title }}</strong></td>
                                    <td>
                                        @if ($page->published == 1)
                                            <span class="badge bg-success"> @lang('common.active') </span>
                                        @else
                                            <span class="badge bg-danger">@lang('common.inactive')</span>
                                        @endif
                                    </td>
                                    <td>{{ $page->created_at->format(setting('datetime_format', 'F d, Y h:ia')) }}</td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content start">
                                            @if (!empty($page->slug))
                                                <a href="{{ route('pages.show', $page->slug) }}" target="_blank"
                                                    class="btn btn-link text-body" role="button" data-toggle="tooltip"
                                                    data-placement="left" title="@lang('common.view')"><span
                                                        class="lni lni-eye"></span></a>
                                            @endif
                                            <a href="{{ route('admin.pages.edit', $page) }}"
                                                class="btn btn-link text-body" role="button" data-toggle="tooltip"
                                                data-original-title="@lang('common.edit')"><span
                                                    class="lni lni-pencil-alt"></span></a>
                                            <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST"
                                                class="d-inline-block">
                                                @method('DELETE')
                                                @csrf<button class="btn btn-link text-danger warning-delete frm-submit"
                                                    role="button" data-bs-toggle="tooltip" data-placement="right"
                                                    title="@lang('common.delete')"><span
                                                        class="lni lni-trash"></span></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="22">@lang('common.noRecordsFund')</td>
                                </tr>
                            @endforelse ($pages as $page)
                        </tbody>
                    </table>
                </div>
                @if ($pages->hasPages())
                    <div class="card-footer">
                        {{ $pages->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
