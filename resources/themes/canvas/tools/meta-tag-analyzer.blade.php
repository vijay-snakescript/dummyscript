<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="box-shadow my-3 py-5">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-group mb-3">
                            <h3 class="h4">@lang('tools.enterPageUrl')</h3>
                        </div>
                        <div class="form-group">
                            <x-text-input type="text" class="form-control" name="domain" id="domain" required
                                value="{{ $results['domain'] ?? old('domain') }}" :error="$errors->has('domain')" />
                            <x-input-error :messages="$errors->get('domain')" class="mt-2" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <x-ad-slot :advertisement="get_advert_model('below-form')" />
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                        @lang('tools.analyzeMetaTags')
                    </x-button>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    @if (isset($results))
        <div class="tool-results-wrapper">
            <x-ad-slot :advertisement="get_advert_model('above-result')" />
            <x-page-wrapper :title="__('common.result')" :sub-title="__('tools.metaTagReport')">
                <div class="result mt-4">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <h2 class="h4 mb-3">@lang('tools.metaTagReportDomain', ['domain' => $results['domain']])</h2>
                                <tbody id="results-container">
                                    <tr>
                                        <th width="175">@lang('tools.metaTitle')</th>
                                        <td>{{ $results['content']['title'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.metaDescription')</th>
                                        <td>{{ $results['content']['description'] ?? '' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12 mt-3">
                            <h2 class="mb-3 h4">@lang('tools.metaTagAnalysis')</h2>
                            <table class="table table-bordered">
                                <tbody id="results-container">
                                    <tr>
                                        <th width="175">@lang('tools.metaTitle')</th>
                                        <td>
                                            <p class="mb-0">
                                                @if (isset($results['content']['title']) && Str::length($results['content']['title']) > 60)
                                                    <span class="text-danger">@lang('tools.metaTitleTagBad', ['max' => 60, 'characters' => Str::length($results['content']['title'])])</span>
                                                @elseif(Str::length($results['content']['title']) != 0)
                                                    <span class="text-info">@lang('tools.metaTitleTagGood', ['max' => 60, 'characters' => Str::length($results['content']['title'])])</span>
                                                @endif
                                            </p>
                                            {{ $results['content']['title'] ?? '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.metaDescription')</th>
                                        <td>
                                            <p class="mb-0">
                                                @if (isset($results['content']['description']) && Str::length($results['content']['description']) > 160)
                                                    <span class="text-danger">@lang('tools.metaDescTagAnalizer', ['greater' => 80, 'smaller' => 160, 'characters' => Str::length($results['content']['description'])])</span>
                                                @elseif(Str::length($results['content']['description']) < 80)
                                                    <span class="text-danger">@lang('tools.metaDescTagAnalizer', ['greater' => 80, 'smaller' => 160, 'characters' => Str::length($results['content']['description'])])</span>
                                                @elseif(Str::length($results['content']['description']) != 0)
                                                    <span class="text-info">@lang('tools.metaDescTagAnalizer', ['greater' => 80, 'smaller' => 160, 'characters' => Str::length($results['content']['description'])])</span>
                                                @endif
                                            </p>
                                            {{ $results['content']['description'] ?? '<span class="text-danger">' . __('tools.metaDescriptionNotFound') . '</span>' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.metaKeyword')</th>
                                        <td>{{ $results['content']['keywords'] ?? __('tools.metaKeywordsNotFound') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.metaViewport')</th>
                                        <td>{{ $results['content']['viewport'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.metaRobot')</th>
                                        <td>
                                            {{ $results['content']['robots'] ?? __('tools.webpageNoRobotsTag') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.openGraph')</th>
                                        <td>{!! !empty($results['content']['og:type'])
                                            ? '<span class="text-success">' . __('tools.openGraphUsed') . '</span>'
                                            : '<span class="text-danger">' . __('tools.openGraphNotUsed') . '</span>' !!}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12 mt-3">
                            <h2 class="h4 mb-3">
                                @lang('tools.webPageAnalysis')
                            </h2>
                            <div class="border px-3 py-2">
                                <div
                                    class="page-url-count fw-bold mb-2{{ $results['content']['internal_links'] > 100 ? ' text-danger' : ' text-info' }}">
                                    {{ __('tools.webPageAnalysisUrlsCount', ['urls' => $results['content']['internal_links'] ?? 0]) }}
                                </div>
                                <div class="page-url-recommendation">
                                    {{ __('tools.webPageAnalysisUrlsCountDesc', ['max' => 100, 'urls' => $results['content']['internal_links'] ?? 0]) }}
                                </div>
                            </div>
                            @if (!empty($results['content']['size']))
                                <div class="border border-top-0 px-3 py-2">
                                    <div class="page-url-size fw-bold mb-2">
                                        {{ __('tools.webPageSizeTitle') }}
                                    </div>
                                    <div class="page-url-recommendation">
                                        {{ __('tools.webPageSizeTitleDesc', ['size' => formatSizeUnits($results['content']['size'])]) }}
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-12 mt-3">
                                <h2 class="h4 mb-3">
                                    @lang('tools.siteSearchEngine')
                                </h2>
                                <div class="border px-3 py-2">
                                    <div class="text-muted site-url">{{ $results['domain'] ?? '' }}</div>
                                    <div class="site-title fw-bold text-primary">
                                        {{ $results['content']['title'] ?? '' }}
                                    </div>
                                    <div class="text-muted site-description">
                                        {{ $results['content']['description'] ?? __('tools.metaDescriptionNotFound') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-page-wrapper>
        </div>
        <x-ad-slot :advertisement="get_advert_model('below-result')" />
    @endif
    <x-tool-content :tool="$tool" />
</x-application-tools-wrapper>
