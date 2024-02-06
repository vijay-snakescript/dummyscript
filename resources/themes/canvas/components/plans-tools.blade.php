@props([
    'plans' => null,
    'properties' => null,
])
<div class="section-padding">
    <div class="container">
          <div class="hero-title center mb-5 bold">
            <h2 class="h1">@lang('tools.chooseYourPlan')</h2>
            <p>@lang('tools.choosePlanDescription')</p>
          </div>
        <div class="row">
            <input type="hidden" id="dateinput" name="date" />
            <div id="content">
                <input id="input-switch-monthly" type="radio" name="input-switch" onclick="ChangeStateInfos()"
                    checked="checked" />
                <input id="input-switch-yearly" type="radio" name="input-switch" onclick="ChangeStateInfos()" />
                <div id="top-switch-labels">
                    <label id="top-switch-label-monthly" for="input-switch-monthly">@lang('tools.monthly')</label>
                    <label id="top-switch-label-yearly" for="input-switch-yearly">@lang('tools.yearly')</label>
                </div>
                <div id="monthly-wrapper">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pricing-wrap">
                                @foreach ($plans as $plan)
                                    <div class="plan{{ $plan->recommended ? ' featured' : '' }}">
                                        <header>
                                            <h4 class="plan-title">
                                                {{ $plan->name }}
                                            </h4>
                                            <div class="plan-cost">
                                                <span class="plan-price">
                                                    <x-money amount="{{ $plan->monthly_price ??  0}}"
                                                        currency="{{ setting('currency', 'usd') }}" convert />
                                                </span>/<span class="plan-type mx-1">@lang('tools.month')</span>
                                            </div>
                                        </header>
                                        <ul class="list-check">
                                            @foreach ($properties as $property)
                                                <li
                                                    class="d-flex justify-content-between @if (!$plan->properties->where('property_id', $property->id)->pluck('value')->max()) cross @else check @endif">
                                                    <span class="prop-name">
                                                        {{ $property->name }}
                                                    </span>
                                                    <span class="prop-max-limit">
                                                        {{ $plan->properties->where('property_id', $property->id)->pluck('value')->max() }}
                                                    </span>
                                                </li>
                                            @endforeach
                                            <li class="@if ($plan->is_ads) check @else cross @endif">
                                                @lang('tools.isAdsAllowed')</li>
                                            <li class="@if ($plan->is_api_allowed) check @else cross @endif">
                                                @lang('tools.isApiAllowed')</li>
                                        </ul>
                                        <div class="plan-select">
                                            <a class="btn btn-primary rounded-pill ps-5 pe-5 mt-3"
                                                href="{{ route('payments.checkout', ['plan_id' => $plan->id, 'type' => 'monthly']) }}">@lang('tools.selectPlan')</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div id="yearly-wrapper">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pricing-wrap">
                                @foreach ($plans as $plan)
                                    <div class="plan{{ $plan->recommended ? ' featured' : '' }}">
                                        <header>
                                            <h4 class="plan-title">
                                                {{ $plan->name }}
                                            </h4>
                                            <div class="plan-cost"><span class="plan-price">
                                                    <x-money amount="{{ $plan->yearly_price ?? 0 }}"
                                                        currency="{{ setting('currency', 'usd') }}" convert />
                                                </span> / <span class="plan-type mx-1">@lang('tools.year')</span></div>
                                        </header>
                                        <ul class="list-check">
                                            @foreach ($properties as $property)
                                                <li
                                                    class="d-flex justify-content-between @if (!$plan->properties->where('property_id', $property->id)->pluck('value')->max()) cross @else check @endif">
                                                    <span class="prop-name">
                                                        {{ $property->name }}
                                                    </span>
                                                    <span class="prop-max-limit">
                                                        {{ $plan->properties->where('property_id', $property->id)->pluck('value')->max() }}
                                                    </span>
                                                </li>
                                            @endforeach
                                            <li class="@if ($plan->is_ads) check @else cross @endif">
                                                @lang('tools.isAdsAllowed')</li>
                                            <li class="@if ($plan->is_api_allowed) check @else cross @endif">
                                                @lang('tools.isApiAllowed')</li>
                                        </ul>
                                        <div class="plan-select">
                                            <a class="btn btn-primary rounded-pill ps-5 pe-5 mt-3"
                                                href="{{ route('payments.checkout', ['plan_id' => $plan->id, 'type' => 'yearly']) }}">@lang('tools.selectPlan')</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
