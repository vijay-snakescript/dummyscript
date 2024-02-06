<x-app-layout>
    <x-manage-filters :search="true" :search-route="route('admin.tools.home-page')" />
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">@lang('common.toolsHomepage') <span class="small text-muted">@lang('common.toolsHomepageDesc')</span></h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-responsive-sm table-quizier mb-0">
                        <thead>
                            <tr>
                                <th width="40">@lang('common.icon')</th>
                                <th>@lang('common.name')</th>
                                <th>@lang('admin.title')</th>
                                <th>@lang('common.views')</th>
                                <th>@lang('common.status')</th>
                                <th width="150">@lang('common.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tools as $tool)
                                <tr>
                                    <td>
                                        @if ($tool->icon_type == 'class')
                                            <i class="an-duotone an-3x an-{{ $tool->icon_class }}"></i>
                                        @elseif ($tool->getFirstMediaUrl('tool-icon'))
                                            <img src="{{ $tool->getFirstMediaUrl('tool-icon') }}"
                                                alt="{{ $tool->name }}" width="36">
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $tool->name }}</strong>
                                        <p class="mb-0">{{ $tool->category->first()->name ?? '' }}</p>
                                    </td>
                                    <td>{{ $tool->meta_title }}</td>
                                    <td>{{ $tool->views->count() ?? 0 }}</td>
                                    <td>
                                        @if ($tool->status)
                                            <span class="badge bg-success"> @lang('common.active') </span>
                                        @else
                                            <span class="badge bg-danger">@lang('common.inactive')</span>
                                        @endif
                                    </td>
                                    <td>

                                        <div class="d-flex align-items-center justify-content start">
                                            @if ($tool->status == 0)
                                                <a href="{{ route('admin.tools.status.change', ['id' => $tool->id, 'status' => 1]) }}"
                                                    class="btn btn-link text-body" role="button" data-toggle="tooltip"
                                                    data-original-title="@lang('common.active')"><span
                                                        class="lni lni-checkmark-circle"></span></a>
                                            @else
                                                <a href="{{ route('admin.tools.status.change', ['id' => $tool->id, 'status' => 0]) }}"
                                                    class="btn btn-link text-body" role="button" data-toggle="tooltip"
                                                    data-original-title="@lang('common.active')"><span
                                                        class="lni lni-circle-minus"></span></a>
                                            @endif
                                            @if (!empty($tool->slug))
                                                <a href="{{ route('tool.show', ['tool' => $tool->slug]) }}"
                                                    target="_blank" class="btn btn-link text-body" role="button"
                                                    data-toggle="tooltip" data-placement="left"
                                                    title="@lang('common.view')"><span class="lni lni-eye"></span></a>
                                            @endif
                                            <a href="{{ route('admin.tools.edit', $tool) }}"
                                                class="btn btn-link text-body" role="button" data-toggle="tooltip"
                                                data-original-title="@lang('common.edit')"><span
                                                    class="lni lni-pencil-alt"></span></a>
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
            </div>
        </div>
    </div>
</x-app-layout>
