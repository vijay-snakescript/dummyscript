<x-app-layout>
    <x-manage-filters :search="true" :search-route="route('admin.advertisements')" />
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                     <h6 class="mb-0">@lang('admin.manageAdvertisements')</h6></div>
                <div class="card-body p-0">
                    <table class="table table-responsive-sm table-quizier mb-0">
                        <thead>
                            <tr>
                                <th>@lang('common.name')</th>
                                <th>@lang('common.title')</th>
                                <th>@lang('admin.impressions')</th>
                                <th>@lang('common.type')</th>
                                <th>@lang('common.status')</th>
                                <th width="150">@lang('common.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($advertisements as $ads_txt)
                                <tr>
                                    <td>{{ $ads_txt->name }}</td>
                                    <td><strong>{{ $ads_txt->title }}</strong></td>
                                    <td>{{ $ads_txt->click_counts }}</td>
                                    <td>{{ $ads_txt->ad_type }}</td>
                                    <td>
                                        @if ($ads_txt->status == 1)
                                            <span class="badge bg-success"> @lang('common.active') </span>
                                        @else
                                            <span class="badge bg-danger">@lang('common.inactive')</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content start">
                                            <a href="{{ route('admin.advertisements.edit', ['advertisement' => $ads_txt]) }}"
                                                class="btn btn-link text-body" role="button" data-toggle="tooltip"
                                                data-original-title="@lang('common.edit')"><span
                                                    class="lni lni-pencil-alt"></span></a>
                                            @if ($ads_txt->status == 0)
                                                <a href="{{ route('admin.advertisements.status.change', ['id' => $ads_txt->id, 'status' => 1]) }}"
                                                    class="btn btn-link text-body" role="button" data-toggle="tooltip"
                                                    data-original-title="@lang('common.active')"><span
                                                        class="lni lni-checkmark-circle"></span></a>
                                            @else
                                                <a href="{{ route('admin.advertisements.status.change', ['id' => $ads_txt->id, 'status' => 0]) }}"
                                                    class="btn btn-link text-body" role="button" data-toggle="tooltip"
                                                    data-original-title="@lang('common.active')"><span
                                                        class="lni lni-circle-minus"></span></a>
                                            @endif
                                            <form action="{{ route('admin.advertisements.destroy', $ads_txt->id) }}"
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
                            @empty
                                <tr>
                                    <td class="text-center" colspan="22">@lang('common.noRecordsFund')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($advertisements->hasPages())
                    <div class="card-footer">
                        {{ $advertisements->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
