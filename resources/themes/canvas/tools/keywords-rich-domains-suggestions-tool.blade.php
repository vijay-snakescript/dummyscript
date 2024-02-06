<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="box-shadow my-3 py-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <x-input-label for="keyword">@lang('tools.enterKeyword')</x-input-label>
                            <x-text-input rows="5" class="form-control" name="keyword" id="keyword" required
                                value="{{ $results['keyword'] ?? old('keyword') }}" :placeholder="__('tools.enterAKeyword')" />
                            <x-input-error :messages="$errors->get('ip')" />
                        </div>
                        <div class="form-group">
                            @foreach ($tlds as $tld)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" name="tlds[]" type="checkbox"
                                        id="type-{{ $loop->index }}" value="{{ $tld }}"
                                        @if (isset($results['type']) && in_array($tld, $results['type'])) checked @endif>
                                    <label class="form-check-label"
                                        for="type-{{ $loop->index }}">{{ $tld }}</label>
                                </div>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
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
                        <div class="col-md-12">
                            <h3>@lang('tools.popularExtensions')</h3>
                            <table class="table table-style mb-0">
                                <thead>
                                    <th>@lang('tools.domainName')</th>
                                    <th width="120">@lang('tools.status')</th>
                                </thead>
                                <tbody id="">
                                    @foreach ($results['domainAddresses']['search'] as $domain)
                                        <tr>
                                            <td>{{ $domain }}</td>
                                            <td class="tld-status" data-domain="{{ $domain }}">
                                                <div class="placeholder-glow"><span class="placeholder col-8"></span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="row my-4">
                        <div class="col-md-3">
                            <ul class="nav nav-pills flex-column" id="myTab" role="tablist">
                                @foreach ($results['tlds'] as $key => $tld)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link w-100 @if ($loop->iteration == 1) active @endif"
                                            id="tld-{{ $key }}-tab" data-bs-toggle="tab"
                                            data-bs-target="#tld-{{ $key }}" type="button" role="tab"
                                            aria-controls="tld-{{ $tld }}"
                                            aria-selected="true">{{ __('tools.tldSuggestions', ['tld' => $tld]) }}</button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-md-9 table-responsive">
                            <div class="tab-content">
                                @foreach ($results['tlds'] as $key => $tld)
                                    <div class="tab-pane @if ($loop->iteration == 1) active @endif"
                                        id="tld-{{ $key }}" role="tabpanel"
                                        aria-labelledby="tld-{{ $key }}-tab">
                                        <table class="table table-style mb-0" id="">
                                            <thead>
                                                <th>@lang('tools.keyword')</th>
                                                <th>@lang('tools.domainName')</th>
                                                <th width="120">@lang('tools.status')</th>
                                            </thead>
                                            <tbody id="">
                                                @foreach ($results['domainAddresses']['suggestions'][$tld] as $suggested)
                                                    @foreach ($suggested['domains'] as $domain)
                                                        <tr>
                                                            @if ($loop->iteration == 1)
                                                                <td class="fw-bold"
                                                                    rowspan="{{ count($suggested['domains']) }}">
                                                                    {{ $suggested['keyword'] }}</td>
                                                            @endif
                                                            <td>{{ $domain }}</td>
                                                            <td class="tld-status" data-domain="{{ $domain }}">
                                                                <div class="placeholder-glow"><span
                                                                        class="placeholder col-8"></span></div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                            </div>
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
                    const elements = document.querySelectorAll('.tld-status');
                    const getReslut = async function(element, cursor, domain) {
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
                        },
                        updateResult = function(element, cursor, res) {
                            element.innerHTML = res
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
                            elements.forEach(element => {
                                getReslut(element, 0, element.dataset.domain)
                            });
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
