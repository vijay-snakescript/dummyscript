<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">@lang('widgets.admin.recentTransactions')</h6>
    </div>
    <div class="card-body card-height">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>@lang('common.title')</th>
                    <th>@lang('admin.price')</th>
                    <th>@lang('admin.user')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $transaction)
                    <tr>
                        <td>
                            {{ $transaction->plan_id != 0 ? $transaction->plan->name : ads_plan()->name }}
                        </td>
                        <td>
                            <x-money :currency="$transaction->currency" :amount="$transaction->amount" convert />
                        </td>
                        <td>{{ $transaction->user->name }}</td>
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
