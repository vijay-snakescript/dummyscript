<h4 class="mb-3">@lang('common.provideCreditCardInfo')</h4>
<div class="col-md-6">
    <div class="form-group mb-3">
        <label class="form-label" for="cc-name">@lang('common.nameOnCard')</label>
        <input type="text" class="form-control" id="cc-name" placeholder="" required name="name_card">
        <small class="text-muted">@lang('common.fullnameOnCard')</small>
        <div class="invalid-feedback">{{ $errors->first('name_card') }}</div>
    </div>
</div>
<div class="col-md-6">
    <div class="form-group mb-3">
        <label class="form-label" for="cc-number">@lang('common.creditCardNumber')</label>
        <input type="number" class="form-control" id="cc-number" placeholder="" required name="card_no">
        <div class="invalid-feedback">{{ $errors->first('card_no') }}</div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group mb-3">
        <label class="form-label" for="cc-expiration">@lang('common.expiryYear')</label>
        <input type="number" class="form-control" id="cc-expiration" placeholder="YYYY" required name="exp_year">
        <div class="invalid-feedback">{{ $errors->first('exp_year') }}</div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group mb-3">
        <label class="form-label" for="cc-year">@lang('common.expiryMonth')</label>
        <input type="number" class="form-control" id="cc-year" placeholder="MM" required name="exp_month">
        <div class="invalid-feedback">{{ $errors->first('exp_month') }}</div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group mb-3">
        <label class="form-label" for="cc-cvv">@lang('common.cVV')</label>
        <input type="number" class="form-control" id="cc-cvv" placeholder="" required name="card_cvc">
        <div class="invalid-feedback">{{ $errors->first('card_cvc') }}</div>
    </div>
</div>
