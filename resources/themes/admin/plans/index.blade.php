<x-app-layout>
    <div class="row">
        <div class="col-md-12">
            <x-manage-filters :search="true" :search-route="route('admin.plans')" />
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0">@lang('admin.managePlans')</h6></div>
                <div class="card-body p-0">
                    <table class="table table-responsive-sm table-quizier mb-0">
                        <thead>
                            <tr>
                                <th>@lang('common.title')</th>
                                <th>@lang('common.description')</th>
                                <th>@lang('admin.monthlyPrice')</th>
                                <th>@lang('admin.yearlyPrice')</th>
                                <th>@lang('common.status')</th>
                                <th width="150">@lang('common.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($plans as $plan)
                                <tr>
                                    <td>{{ $plan->name }}</td>
                                    <td>{{ $plan->description }}</td>
                                    <td><x-money amount="{{ $plan->monthly_price ?? 0 }}" currency="{{ setting('currency', 'usd') }}" convert /></td>
                                    <td><x-money amount="{{ $plan->yearly_price ?? 0 }}" currency="{{ setting('currency', 'usd') }}" convert /></td>
                                    <td>{{ $plan->discount }}</td>
                                    <td>
                                        @if ($plan->status == 1)
                                            <span class="badge me-1 bg-success">@lang('common.active')</span>
                                        @else
                                            <span class="badge me-1 bg-danger">@lang('common.inactive')</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content start">
                                            <a href="{{ route('admin.plans.edit', $plan) }}"
                                                class="btn btn-link text-body" role="button" data-toggle="tooltip"
                                                data-original-title="@lang('common.edit')"><span
                                                    class="lni lni-pencil-alt"></span></a>
                                            @if ($plan->status == 0)
                                                <a href="{{ route('admin.plans.status.change', ['id' => $plan->id, 'status' => 1]) }}"
                                                    class="btn btn-link text-body" role="button" data-toggle="tooltip"
                                                    data-original-title="@lang('common.active')"><span
                                                        class="lni lni-checkmark-circle"></span></a>
                                            @else
                                                <a href="{{ route('admin.plans.status.change', ['id' => $plan->id, 'status' => 0]) }}"
                                                    class="btn btn-link text-body" role="button" data-toggle="tooltip"
                                                    data-original-title="@lang('common.active')"><span
                                                        class="lni lni-circle-minus"></span></a>
                                            @endif

                                            <form action="{{ route('admin.plans.destroy', $plan->id) }}" method="POST"
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
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($plans->hasPages())
                    <div class="card-footer">
                        {{ $plans->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
