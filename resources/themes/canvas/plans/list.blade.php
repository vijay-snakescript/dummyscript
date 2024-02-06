<x-application-no-widget-wrapper>
    <x-page-wrapper :title="__('tools.chooseYourPlan')" :sub-title="__('tools.choosePlanDescription')" heading="h1" hero-class="mt-5 mb-5 center">
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
                                                    <x-money amount="{{ $plan->monthly_price ?? 0 }}"
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
    </x-page-wrapper>
    @unless($faqs->isEmpty())
        <x-page-wrapper :title="__('tools.frequentlyAskedQuestions')" :sub-title="__('tools.frequentlyAskedQuestionsDesc')" heading="h2" hero-class="mt-5 mb-5 center">
            <div class="accordion accordion-flush" id="faqs">
                @foreach ($faqs as $faq)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq-head-{{ $faq->id }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq-{{ $faq->id }}" aria-expanded="false"
                                aria-controls="faq-{{ $faq->id }}">
                                {{ $faq->question }}
                            </button>
                        </h2>
                        <div id="faq-{{ $faq->id }}" class="accordion-collapse collapse"
                            aria-labelledby="faq-head-{{ $faq->id }}" data-bs-parent="#faqs" style="">
                            <div class="accordion-body">{!! $faq->answer !!}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-page-wrapper>
    @endunless
    @unless($tools->isEmpty())
        <x-page-wrapper :title="__('tools.whatAreYouGetting')" heading="h2" hero-class="mt-5 mb-3 center">
            <div class="row">
                @foreach ($tools->chunk(ceil($tools->count() / 3)) as $chunk)
                    <div class="col-md-4">
                        <ul class="list-check">
                            @foreach ($chunk as $tool)
                                <li class="check-single">
                                    {{ $tool->name }}
                                    {{-- <span class="text-muted">{{ $tool->du_tool }}</span> --}}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </x-page-wrapper>
    @endunless
</x-application-no-widget-wrapper>
