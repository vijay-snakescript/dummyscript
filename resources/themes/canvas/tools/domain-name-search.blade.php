<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="box-shadow my-3 py-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <h3 class="h4">@lang('tools.enterDomainNameOrKeyword')</h3>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <x-text-input rows="5" class="form-control" name="domain" id="domain" required
                                value="{{ $results['domain'] ?? old('domain') }}" :placeholder="__('tools.enterAKeyword')" />
                            <x-input-error :messages="$errors->get('ip')" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <select name="type" id="type" required class="form-control form-select">
                                <option selected="selected" value=".com">.com</option>
                                <option @if (isset($results['type']) && $results['type'] == '.net') selected @endif value=".net">.net</option>
                                <option @if (isset($results['type']) && $results['type'] == '.org') selected @endif value=".org">.org</option>
                                <option @if (isset($results['type']) && $results['type'] == '.us') selected @endif value=".us">.us</option>
                                <option @if (isset($results['type']) && $results['type'] == '.info ') selected @endif value=".info ">.info</option>
                                <option @if (isset($results['type']) && $results['type'] == '.co.in') selected @endif value=".co.in">.co.in</option>
                                <option @if (isset($results['type']) && $results['type'] == '.me') selected @endif value=".me">.me</option>
                                <option @if (isset($results['type']) && $results['type'] == '.co') selected @endif value=".co">.co</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>
                    </div>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                        @lang('tools.checkDomainName')
                    </x-button>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    @if (isset($results))
        <div class="tool-results-wrapper">
            <x-ad-slot :advertisement="get_advert_model('above-result')" />
            <x-page-wrapper :title="__('common.result')">
                <div class="result">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>@lang('tools.popularExtensions')</h3>
                            <table class="table table-style mb-0">
                                <thead>
                                    <th></th>
                                    <th>@lang('tools.domainName')</th>
                                    <th>@lang('tools.status')</th>
                                </thead>
                                <tbody id="results-container">

                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-3 loader-search" id="loader">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">@lang('common.loading')</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h3>@lang('tools.suggestions')</h3>
                            <table class="table table-style mb-0">
                                <thead>
                                    <th></th>
                                    <th>@lang('tools.domainName')</th>
                                    <th>@lang('tools.status')</th>
                                </thead>
                                <tbody id="suggestions-container">
                                    @if (!$results['has_suggestions'])
                                        <tr>
                                            <td class="text-center" colspan="4">@lang('tools.noSuggestions')</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            @if ($results['has_suggestions'])
                                <div class="d-flex justify-content-center mt-3 loader-search" id="loader-1">
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">@lang('common.loading')</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </x-page-wrapper>
        </div>
        <x-ad-slot :advertisement="get_advert_model('below-result')" />
    @endif
    <x-tool-content :tool="$tool" />
    @if (isset($results))
        @push('page_scripts')
            <script>
                const APP = function() {
                    const search_domains = {!! $results['domainAddresses'] !!};
                    const getReslut = async function(element, cursor, domains) {
                            if (domains.length == cursor) {
                                return;
                            }
                            const domain = domains[cursor]
                            initResult(element, cursor, domain)
                            await axios.post(
                                    '{{ route('tool.postAction', ['tool' => $tool->slug, 'action' => 'get-domain-name-search']) }}', {
                                        domain: domain
                                    })
                                .then((res) => {
                                    updateResult(element, cursor, res.data)
                                })
                                .catch((err) => {
                                    resultError(element, cursor)
                                })
                            cursor++
                            if (cursor < domains.length) {
                                getReslut(element, cursor, domains)
                            } else {
                                document.querySelector(`#${element}`).parentNode.parentNode.querySelector('.loader-search')
                                    .classList.add('d-none')
                            }
                        },
                        initResult = function(element, cursor, domain) {
                            const html = `<tr>
                                <td>${cursor+1}</td>
                                <td>${domain || '-'}</td>
                                <td width="130" class="text-start ps-3 domain-status-${cursor}-${element}"><div class="placeholder-glow"><span class="placeholder col-8"></span></div></td>
                            </tr>`;

                            document.querySelector(`#${element}`).innerHTML += html
                        },
                        updateResult = function(element, cursor, res) {
                            document.querySelector(`.domain-status-${cursor}-${element}`).innerHTML = res
                                .status ||
                                ''
                        },
                        resultError = function(element, cursor) {
                            const status = `<span class="text-danger">{{ __('common.error') }}</span>`
                            updateResult(element, cursor, {
                                status: status
                            })
                        };

                    return {
                        init: function() {
                            getReslut('results-container', 0, search_domains.search)
                            getReslut('suggestions-container', 0, search_domains.suggestions)
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
