@if (!empty($advertisement) && ((Auth::check() && Auth::user()->isAdsAllowed) || !Auth::check()))
    <div
        class="ad-card {{ $advertisement->type == 1 && !empty($advertisement->options['image']) ? ' text-ad' : ' block' }}">
        <div class="ad-card-outer">
            <div class="ad-card-wrap bg-info-light">
                @if (($advertisement->type == 2 || $advertisement->type == 1) && !empty($advertisement->options['image']))
                    <a class="ad-card-img" href="{{ $advertisement->options['target_url'] }}">
                        <div>
                            <img class="img-fluid" src="{{ $advertisement->options['image'] }}"
                                alt="{!! $advertisement->title !!}" />
                        </div>
                    </a>
                @endif
                @if ($advertisement->type == 1)
                    <a href="{{ $advertisement->options['target_url'] }}" target="_blank"
                        rel="nofollow noopener noreferrer">
                        <div class="ad-card-content">
                            <div class="ad-card-title">{!! $advertisement->title !!}</div>
                            <div class="ad-card-link">{{ extractHostname($advertisement->options['target_url']) }}</div>
                            @if (!empty($advertisement->options['description']))
                                <div class="ad-card-text">{!! $advertisement->options['description'] !!}</div>
                            @endif
                        </div>
                    </a>
                @endif
                <a href="{{ route('ads.remove') }}" class="ad-remove-button bg-info-light" tabindex="-1" target="_blank">@lang('common.removeAds')</a>
            </div>
            @if ($advertisement->type == 3 && !empty($advertisement->options['code']))
                {!! $advertisement->options['code'] !!}
            @endif
        </div>
    </div>
@endif
