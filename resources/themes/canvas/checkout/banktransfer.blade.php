<h4 class="mb-3">@lang('common.bankTransferInformation')</h4>
<hr class="mb-4">
<div class="col-md-12 mb-3 font-weight-bold">
    {!! nl2br(setting('bank_transfer_details')) !!}
</div>

<div class="col-md-12">
    <div class="alert alert-primary" role="alert">
        <h6>@lang('common.bankTransferAlert')</h6>
    </div>
    <x-input-label>@lang('common.providerId')</x-input-label>
    <input type="text" readonly name="transaction_id" value="{{ \Str::random(15) }}" class="form-control" />
</div>
