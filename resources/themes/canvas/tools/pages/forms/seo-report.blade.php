<div class="analysis-search-wrap" id="try-it-free">
    <div class="analysis-search">
        <x-form method="post" :route="route('front.index.action')">
            <div class="input-group input-group-lg">
                <x-text-input class="form-control" name="url" id="url" type="url" required
                    value="{{ $results['url'] ?? old('url') }}" :placeholder="__('tools.enterOrPasteUrl')" />
                <x-button type="submit" class="btn btn-secondary">
                    @lang('tools.generateReport')
                </x-button>
            </div>
            <x-input-error :messages="$errors->get('url')" />
        </x-form>
    </div>
    @if (isset($results))
        <div class="container">
            <div class="tool-results-wrapper result report-result">
                <x-page-wrapper class="mb-0 pb-0" :title="__('common.result')">
                    <div class="row">
                        <div class="d-flex align-items-center pb-3">
                            <div class="d-flex align-items-center gap-1">
                                <x-print-button id="printReport" type="button" />
                            </div>
                        </div>
                    </div>
                </x-page-wrapper>
                <div
                    class="d-flex position-relative position-sticky sticky-top report-menu border-bottom border-top mb-4">
                    <nav id="seo-report-navbar" class="navbar sticky-top navbar-expand-lg navbar-expand-xl w-100">
                        <div class="d-flex align-items-center d-lg-none font-weight-medium ms-3 text-muted">
                            @lang('common.menu')
                        </div>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation"> <span
                                class="navbar-toggler-icon"></span></button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0 flex-wrap justify-content-around w-100">
                                <li class="nav-item">
                                    <a class="nav-link text-center" href="#overview">
                                        <i class="an an-overview"></i>
                                        <span>@lang('seo.overview')</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-center" href="#seo">
                                        <i class="an an-search"></i>
                                        <span>@lang('seo.seo')</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-center" href="#performance">
                                        <i class="an an-performance"></i>
                                        <span>@lang('seo.speedOptimizations')</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-center" href="#security">
                                        <i class="an an-security"></i>
                                        <span>@lang('seo.serverAndSecurity')</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-center" href="#advance">
                                        <i class="an an-miscellaneous"></i>
                                        <span>@lang('seo.advance')</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
                <div class="pb-1 report-contents">
                    <x-seo-tool-result :results="$results" :tool="$tool" />
                </div>
            </div>
        </div>
    @endif
</div>
