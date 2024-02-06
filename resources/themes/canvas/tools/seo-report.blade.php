<x-application-tools-wrapper>
    <x-ad-slot :advertisement="get_advert_model('above-tool')" />
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="row">
                <div class="col-md-12">
                    <x-input-label>@lang('tools.enterWebsiteUrl')</x-input-label>
                    <div class="input-group">
                        <x-text-input class="form-control" name="url" id="url" type="url" required
                            value="{{ $results['url'] ?? old('url') }}" :placeholder="__('tools.enterOrPasteUrl')" />
                        <x-button type="submit" class="btn btn-primary">
                            @lang('tools.generateReport')
                        </x-button>
                    </div>
                    <x-input-error :messages="$errors->get('url')" />
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    @if (isset($results))
        <x-ad-slot :advertisement="get_advert_model('above-result')" />
        <div class="result report-result">
            <x-page-wrapper class="mb-0 pb-0" :title="__('common.result')">
                <div class="row">
                    <div class="d-flex align-items-center pb-3">
                        <h1 class="h2 mb-0 flex-grow-1">{{ $results['result']['domainname'] }}</h1>
                        <div class="d-flex align-items-center gap-1">
                            <x-reload-button :link="route('tool.show', ['tool' => $tool->slug])" :tooltip="__('seo.generateNewReport')" />
                            <x-print-button id="printReport" type="button" />
                        </div>
                    </div>
                </div>
            </x-page-wrapper>
            <div class="d-flex position-relative position-sticky sticky-top report-menu border-bottom border-top mb-4">
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
            <x-seo-tool-result :results="$results" :tool="$tool"></x-seo-tool-result>
        </div>
        <x-ad-slot :advertisement="get_advert_model('below-result')" />
    @endif
    <x-tool-content :tool="$tool" />
    @if (isset($results))
        @push('page_scripts')
            <script>
                const APP = function() {
                    const resources =
                        '<link rel="stylesheet" href="{{ Vite::asset('resources/themes/canvas/assets/sass/app.scss') }}" />';
                    const printReport = function() {
                            let printable = document.querySelector('.printable-container').cloneNode(true)

                            printable.querySelectorAll('.col-auto').forEach(element => {
                                element.remove()
                            });
                            printable.querySelectorAll('.collapse').forEach(element => {
                                element.classList.remove('collapse')
                            });
                            printable.querySelectorAll('.an-light').forEach(element => {
                                element.classList.remove('an-light')
                            });

                            let wrapper = document.createElement('div')
                            let children = document.createElement('div')
                            children.className = 'report-result container'
                            children.appendChild(printable)
                            wrapper.appendChild(children)
                            ArtisanApp.printResult(wrapper, {
                                title: '{{ __('seo.seoReportForDomain', ['domain' => $results['result']['domainname']]) }}',
                                header_code: resources
                            })
                            // document.querySelector('body').appendChild(wrapper)
                        },
                        attachEvents = function() {
                            document.querySelector('#printReport').addEventListener('click', elem => {
                                printReport()
                            })
                        };

                    return {
                        init: function() {
                            attachEvents();
                        }
                    }
                }();

                document.addEventListener("DOMContentLoaded", function(event) {
                    APP.init();
                });
            </script>
        @endpush
    @endif
</x-application-tools-wrapper>
