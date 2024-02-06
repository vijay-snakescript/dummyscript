<x-app-layout>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">@lang('admin.managePlans')</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-responsive-sm table-quizier mb-0">
                        <thead>
                            <tr>
                                <th>@lang('common.title')</th>
                                <th>@lang('common.description')</th>
                                <th>@lang('admin.price')</th>
                                <th>@lang('admin.user')</th>
                                <th>@lang('admin.subscribedOn')</th>
                                <th>@lang('admin.expiryDate')</th>
                                <th>@lang('common.status')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $transaction)
                                <tr>
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
                                    <td>
                                        {{ $transaction->user->name }}</td>
                                    <td>
                                        {{ $transaction->created_at ? $transaction->created_at->format(setting('datetime_format')) : '-' }}
                                    </td>
                                    <td>
                                        {{ $transaction->expiry_date ? $transaction->expiry_date->format(setting('datetime_format')) : '-' }}
                                    </td>
                                    <td>
                                        @if ($transaction->status == 1)
                                            <span class="badge me-1 bg-success">@lang('common.active')</span>
                                        @else
                                            <span class="badge me-1 bg-danger">@lang('common.inactive')</span>
                                        @endif
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
