<x-user-profile :title="__('tools.transactionsList')">
    <table class="table table-style">
        <thead>
            <tr>
                <th>#</th>
                <th><span class="ps-2">@lang('tools.plan')</span></th>
                <th>@lang('tools.subscriptionDate')</th>
                <th>@lang('tools.expiryDate')</th>
                <th>@lang('tools.status')</th>
                <th>@lang('tools.invoice')</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $transaction)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><span class="ps-2">
                            @if ($transaction->plan_id == 0)
                                {{ ads_plan()->name }}
                            @else
                                {{ $transaction->plan->name }}
                            @endif
                        </span></td>
                    <td>{{ $transaction->created_at }}</td>
                    <td>{{ $transaction->expiry_date }}</td>
                    <td>
                        @if ($transaction->status == 0)
                            <span class="badge badge-warning rounded-pill">Pending</span>
                        @endif
                        @if ($transaction->status == 1)
                            <span class="badge badge-success rounded-pill">Active</span>
                        @endif
                        @if ($transaction->status == 2)
                            <span class="badge badge-danger rounded-pill">Expired/Canceled</span>
                        @endif
                    </td>
                    <td>
                        <a class="btn btn-outline-primary rounded-circle"
                            href="{{ route('transaction.invoice', $transaction->id) }}" type="button" id="button"
                            data-toggle="tooltip" aria-label="Download">
                            <i class="an an-eye"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center">@lang('common.noRecordsFund')</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-user-profile>
