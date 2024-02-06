<x-application-tools-wrapper>
    <x-ad-slot :advertisement="get_advert_model('above-tool')" />
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="row">
                <div class="col-md-12">
                    <x-input-label>@lang('tools.wordpressDetectorTitle')</x-input-label>
                    <div class="input-group">
                        <x-text-input class="form-control" name="url" id="url" type="url" required
                            value="{{ $results['url'] ?? old('url') }}" :placeholder="__('tools.enterWebsiteUrl')" />
                        <x-button type="submit" class="btn btn-primary">
                            @lang('common.getInfo')
                        </x-button>
                    </div>
                    <x-input-error :messages="$errors->get('url')" />
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    @if (isset($results))
        <x-page-wrapper :title="__('common.result')" :sub-title="__('tools.wpThemeDescription', ['host' => $results['hostname']])">
            <div class="wp-detail result mt-4">
                <x-ad-slot :advertisement="get_advert_model('above-result')" />
                <div class="row">
                    <div class="col-md-12">
                        <div class="box-shadow mb-3">
                            @if ($theme)
                                <div class="result-detail d-flex justify-content-center">
                                    <div class="detail">
                                        <div class="image">
                                            <img class="laptop" src="{{ theme_url('themes/default/images/mac.svg') }}"
                                                alt="{{ $tool->name }}">
                                            <img class="screenshot" src="{{ $theme->screenshot_url }}"
                                                alt="{{ $theme->name ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="theme-detail">
                                        <div class="title">
                                            <div class="download">
                                                <a class="btn btn-primary rounded-pill" href="{{ $theme->theme_uri }}"
                                                    target="_blank">@lang('common.demo')</a>
                                                @if (!empty($theme->download_link))
                                                    <a class="btn btn-primary rounded-pill"
                                                        href="{{ $theme->download_link }}"
                                                        target="_blank">@lang('common.download')</a>
                                                @endif
                                            </div>
                                            <h3 class="mb-3"><strong>{{ $theme->name ?? '' }}</strong></h3>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h6>
                                                    <strong>@lang('common.version'):</strong> {{ $theme->version ?? '-' }}
                                                </h6>
                                            </div>
                                            @if (!empty($theme->last_updated_time))
                                                <div class="col-md-12">
                                                    <h6>
                                                        <strong>@lang('common.lastUpdated'):</strong>
                                                        {{ \Carbon\Carbon::parse($theme->last_updated_time)->diffForHumans() }}
                                                    </h6>
                                                </div>
                                            @endif
                                            <div class="col-md-12">
                                                <h6>
                                                    <strong>@lang('common.author'):</strong>
                                                    @if (!empty($theme->author_uri))
                                                        <a href="{{ $theme->author_uri }}"
                                                            target="_blank">{{ $theme->author_name ?? $theme->author }}</a>
                                                    @else
                                                        {{ $theme->author_name ?? $theme->author }}
                                                    @endif
                                                </h6>
                                            </div>
                                            @if (!empty($theme->rating))
                                                <div class="col-md-12">
                                                    <h6 class="d-flex align-items-center">
                                                        <strong class="me-1">@lang('common.rating'):</strong>
                                                        <div class="star-ratings me-1">
                                                            <div class="fill-ratings"
                                                                style="width: {{ $theme->rating }}%;">
                                                                <span>★★★★★</span>
                                                            </div>
                                                            <div class="empty-ratings">
                                                                <span>★★★★★</span>
                                                            </div>
                                                        </div>
                                                        @if (!empty($theme->num_ratings))
                                                            <span
                                                                class="fw-bold">({{ format_number($theme->num_ratings) }})</span>
                                                        @endif
                                                    </h6>
                                                </div>
                                            @endif
                                            <div class="col-md-12">
                                                <h6><strong>@lang('common.description'):</strong></h6>
                                                {!! $theme->description !!}
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <h6><strong>@lang('common.license'):</strong> {{ $theme->license }}</h6>
                                                <h6><strong>@lang('common.licenseUri'):</strong> {{ $theme->license_uri }}</h6>
                                            </div>
                                            @if (!empty($theme->downloaded))
                                                <div class="col-md-12">
                                                    <h6>
                                                        <strong>@lang('common.downloaded'):</strong>
                                                        {{ format_number($theme->downloaded) }}
                                                    </h6>
                                                </div>
                                            @endif
                                        </div>
                                        @if (is_array($theme->tags) && count($theme->tags) > 0)
                                            <div class="row">
                                                <div class="col-md-12 mt-3">
                                                    <h6><strong>@lang('admin.tags'):</strong></h6>
                                                    @foreach ($theme->tags as $tag)
                                                        <span
                                                            class="badge rounded-pill bg-primary">{{ Str::of($tag)->trim()->title() }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="result-detail text-danger fw-bold text-center">
                                    @lang('tools.notFoundOrUsingCustomTheme', ['host' => $results['hostname']])
                                </div>
                            @endif
                        </div>

                        <div class="box-shadow mb-3">
                            <h3 class="mb-4"><strong>@lang('tools.numberOfPluginsFound', ['count' => count($plugins)])</strong></h3>
                            <div class="row match-height">
                                @forelse ($plugins as $plugin)
                                    <div class="col-md-6">
                                        <div class="plugin equal-height">
                                            <div class="plugin-img"
                                                style="background-image: url('{{ $plugin->screenshot_url ?? theme_url('themes/default/images/no-image-cover.jpg') }}')">
                                                <div class="plugin-title text-truncate">
                                                    {!! $plugin->name !!}
                                                </div>
                                            </div>
                                            <div class="plugin-details">
                                                <h3 class="mt-2">{!! $plugin->name !!}</h3>
                                                @if (isset($plugin->rating))
                                                    <div class="rating">
                                                        <div class="star-ratings me-1">
                                                            <div class="fill-ratings"
                                                                style="width: {{ $plugin->rating }}%;">
                                                                <span>★★★★★</span>
                                                            </div>
                                                            <div class="empty-ratings">
                                                                <span>★★★★★</span>
                                                            </div>
                                                        </div>
                                                        @if (!empty($plugin->num_ratings))
                                                            <span
                                                                class="fw-bold small">({{ format_number($plugin->num_ratings) }})</span>
                                                        @endif
                                                    </div>
                                                @endif
                                                <p>{!! $plugin->description !!}</p>
                                                <div class="tags">
                                                    @if (!empty($plugin->type))
                                                        <span>
                                                            <i class="an an-plug"></i>
                                                            {{ $plugin->type }}
                                                        </span>
                                                    @endif
                                                </div>
                                                @if (!empty($plugin->download_link))
                                                    <a href="{{ $plugin->download_link }}"
                                                        class="btn btn-primary rounded-pill mt-3"
                                                        target="_blank">@lang('common.download')</a>
                                                    @if (!empty($plugin->homepage))
                                                        <a href="{{ $plugin->homepage }}"
                                                            class="btn btn-primary rounded-pill mt-3"
                                                            target="_blank">@lang('tools.learnMore')</a>
                                                    @endif
                                                @else
                                                    <a href="https://www.google.com/search?q={{ preg_replace('/\s+/', '+', "{$plugin->name} Wordpress plugin") }}"
                                                        class="btn btn-primary rounded-pill mt-3"
                                                        target="_blank">@lang('tools.searchPlugin')</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-md-12">
                                        @lang('tools.noPluginsFound')
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-page-wrapper>
        <x-ad-slot :advertisement="get_advert_model('below-result')" />
    @endif
    <x-tool-content :tool="$tool" />
</x-application-tools-wrapper>
