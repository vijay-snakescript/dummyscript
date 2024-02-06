<x-app-layout>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">@lang('admin.managePlans')</h6>
                </div>
                <div class="card-body px-3">
                    <table class="table table-responsive-sm mb-0">
                        <thead>
                            <tr class="align-middle">
                                <th>@lang('common.title')</th>
                                <th>@lang('common.description')</th>
                                <th>@lang('admin.price')</th>
                                <th>@lang('admin.user')</th>
                                <th>@lang('admin.subscribedOn')</th>
                                <th>@lang('admin.transactionId')</th>
                                <th>@lang('common.status')</th>
                                <th width="150">@lang('common.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $transaction)
                                <tr class="align-middle">
                                    <td>
                                        {{ $transaction->plan_id != 0 ? $transaction->plan->name : ads_plan()->name }}
                                    </td>
                                    <td>
                                        {{ $transaction->plan_id != 0 ? $transaction->plan->description : ads_plan()->description }}
                                    </td>
                                    <td>
                                        <x-money amount="{{ $transaction->amount }}"
                                            currency="{{ $transaction->currency }}" convert />
                                    </td>
                                    <td>{{ $transaction->user->name }}</td>
                                    <td>{{ $transaction->created_at->format(setting('datetime_format')) }}</td>
                                    <td>{{ $transaction->transaction_id }}</td>
                                    <td>
                                        @if ($transaction->status == 1)
                                            <span class="badge me-1 bg-success">@lang('common.active')</span>
                                        @elseif($transaction->status == 5)
                                            <span class="badge me-1 bg-warning">@lang('common.pending')</span>
                                        @else
                                            <span class="badge me-1 bg-danger">@lang('common.inactive')</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content start">
                                            @if ($transaction->status == 5)
                                                <a href="{{ route('admin.banktransfer.status.change', ['id' => $transaction->id, 'status' => 0]) }}"
                                                    class="btn btn-danger text-white me-2" role="button" data-coreui-toggle="tooltip"
                                                    title="@lang('common.active')"><span
                                                        class="lni lni-circle-minus"></span></a>

                                                <a href="{{ route('admin.banktransfer.status.change', ['id' => $transaction->id, 'status' => 1]) }}"
                                                    class="btn btn-success text-white" role="button" data-coreui-toggle="tooltip"
                                                    title="@lang('common.active')"><span
                                                        class="lni lni-checkmark-circle"></span></a>
                                            @endif

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
                @if ($transactions->hasPages())
                    <div class="card-footer">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
