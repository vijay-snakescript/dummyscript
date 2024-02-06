<x-app-layout>
    <x-manage-filters :button="__('common.createNew')" :route="route('admin.faqs.create')" :search="true" :search-route="route('admin.faqs.index')" />
    <div class="row">
        <!-- <div class="col-md-12">
            <div class="d-flex items-align-center justify-content-between mb-3">
                <h6>
                    @lang('admin.faqs')
                </h6>
            </div>
        </div> -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h6 class="mb-0">@lang('admin.faqs')</h6></div>
                <div class="card-body p-0">
                    <table class="table table-responsive-sm table-style sorted_table mb-0" id="sorted_table">
                        <thead>
                            <tr>
                                <th>@lang('admin.questions')</th>
                                <th>@lang('common.status')</th>
                                <th>@lang('admin.pricingPage')</th>
                                <th width="200">@lang('common.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($faqs as $faq)
                                <tr>
                                    <td>{{ $faq->question }} </td>
                                    <td>
                                        @if ($faq->status)
                                            <span class="badge bg-success"> @lang('common.active') </span>
                                        @else
                                            <span class="badge bg-danger">@lang('common.inactive')</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($faq->pricing)
                                            <span class="badge bg-success"> @lang('common.yes') </span>
                                        @else
                                            <span class="badge bg-danger">@lang('common.no')</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($faq->status)
                                            <a href="{{ route('admin.faqs.changeStatus', [$faq->id, 0]) }}"
                                                class="btn btn-link text-body" role="button"
                                                data-coreui-toggle="tooltip" title="@lang('common.deactivate')"><i
                                                    class="lni lni-ban"></i></a>
                                        @else
                                            <a href="{{ route('admin.faqs.changeStatus', [$faq->id, 1]) }}"
                                                class="btn btn-link text-body" role="button"
                                                data-coreui-toggle="tooltip" title="@lang('common.activate')">
                                                <i class="lni lni-checkmark-circle"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.faqs.edit', $faq->id) }}"
                                            class="btn btn-link text-body" role="button" data-coreui-toggle="tooltip"
                                            title="@lang('common.delete')"><i class="lni lni-pencil"></i></a>
                                        <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST"
                                            class="d-inline-block">
                                            @method('DELETE')
                                            @csrf<button class="btn btn-link text-danger warning-delete frm-submit"
                                                data-coreui-toggle="tooltip" data-placement="right"
                                                title="@lang('common.delete')"><i class="lni lni-trash-can"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center skip-last" colspan="6">@lang('common.noRecordsFund')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($faqs->hasPages())
                    <div class="card-footer">
                        {{ $faqs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
