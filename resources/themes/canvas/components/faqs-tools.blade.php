@props([
    'faqs' => null,
])
@if ($faqs->count() > 0)
    <div class="resizing-accordion section-padding">
        <div class="container">
            <div class="hero-title center my-5 bold">
                <h2 class="h1">@lang('tools.frequentlyAskedQuestions')</h2>
                <p>@lang('tools.frequentlyAskedQuestionsDesc')</p>
            </div>
            <div class="mb-3 mt-4">
                <div class="accordion accordion-flush" id="faqs">
                    @foreach ($faqs as $faq)
                        <div class="accordion-item box-shadow p-0">
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
            </div>
        </div>
    </div>
@endif
