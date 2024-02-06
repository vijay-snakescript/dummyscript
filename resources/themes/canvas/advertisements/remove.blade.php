<x-application-no-widget-wrapper>
    <x-ad-slot />
    <x-page-wrapper>
        <div class="hero-title center mb-5 mt-5">
            <h1>@lang('tools.removeAdsTitle')</h1>
            <h5>@lang('tools.adsFreeExperience')</h5>
            <p>@lang('tools.removeAdsTitleSub')</p>
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
                <div id="monthly-wrapper" class="mt-0">
                    <div class="ads-free-wrap">
                        <div class="plan">
                            <header>
                                <div class="plan-cost"><span class="plan-price">
                                    <x-money amount="{{ $plan->monthly_price ?? 0 }}" currency="{{ setting('currency', 'usd') }}" convert />
                                    </span>
                                    <span class="plan-type">/ @lang('tools.month')</span>
                                </div>
                            </header>
                            <ul class="list-check">
                                <li class="check">@lang('tools.removeAdsFeature1')</li>
                                <li class="check">@lang('tools.removeAdsFeature2')</li>
                            </ul>
                        </div>
                        <div class="plan">
                            <ul class="list-check">
                                <li class="check">@lang('tools.removeAdsFeature3')</li>
                                <li class="check">@lang('tools.removeAdsFeature4')</li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="plan-select">
                            <a class="btn btn-primary rounded-pill ps-5 pe-5 mt-4"
                                href="{{ route('payments.checkout', ['plan_id' => '0', 'type' => 'monthly']) }}">@lang('tools.checkoutTitle')</a>
                        </div>
                    </div>
                </div>
                <div id="yearly-wrapper" class="mt-0">
                    <div class="ads-free-wrap">
                        <div class="plan">
                            <header>
                                <div class="plan-cost"><span
                                        class="plan-price">
                                        <x-money amount="{{ $plan->yearly_price ?? 0 }}" currency="{{ setting('currency', 'usd') }}" convert />
                                        </span><span
                                        class="plan-type">/ @lang('tools.year')</span></div>
                            </header>
                            <ul class="list-check">
                                <li class="check">@lang('tools.removeAdsFeature1')</li>
                                <li class="check">@lang('tools.removeAdsFeature2')</li>
                            </ul>
                        </div>
                        <div class="plan">
                            <ul class="list-check">
                                <li class="check">@lang('tools.removeAdsFeature3')</li>
                                <li class="check">@lang('tools.removeAdsFeature4')</li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="plan-select">
                            <a class="btn btn-primary rounded-pill ps-5 pe-5 mt-4"
                                href="{{ route('payments.checkout', ['plan_id' => '0', 'type' => 'yearly']) }}">@lang('tools.checkoutTitle')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-page-wrapper>
    @unless($faqs && $faqs->isEmpty())
        <x-ad-slot />
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
</x-application-no-widget-wrapper>
