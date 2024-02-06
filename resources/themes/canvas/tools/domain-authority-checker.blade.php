<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-tool-property-display :tool="$tool" name="" label="" :plans="true" upTo="" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="box-shadow my-3 py-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <h3 class="h4">@lang('tools.enterDomainLimitLabel', ['count' => $tool->no_domain_tool])</h3>
                        </div>
                        <div class="form-group">
                            <x-textarea-input rows="5" class="form-control" name="domain" id="domain" required>
                                {{ $results['domain'] ?? old('domain') }}
                            </x-textarea-input>
                        </div>
                        <x-input-error :messages="$errors->get('domain')" class="mt-2" />
                    </div>
                </div>
            </div>
            <div class="row">
                <x-ad-slot :advertisement="get_advert_model('below-form')" />
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                        @lang('tools.checkAuthority')
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
                    <table class="table table-style mb-0">
                        <thead>
                            <th></th>
                            <th>@lang('tools.domainIp')</th>
                            <th>@lang('tools.daName')</th>
                            <th>@lang('tools.paName')</th>
                            <th>@lang('tools.mrName')</th>
                            <th>@lang('tools.linking')</th>
                        </thead>
                        <tbody id="results-container">

                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center mt-3" id="loader">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">@lang('common.loading')</span>
                        </div>
                    </div>
                </div>
            </x-page-wrapper>
        </div>
    @endif
    <x-ad-slot :advertisement="get_advert_model('below-result')" />
    <x-tool-content :tool="$tool" />
    @if (isset($results))
        @push('page_scripts')
            <script>
                const APP = function() {
                    const domains = {!! $results['domainAddresses'] !!};
                    var cursor = 0;
                    const getReslut = async function() {
                            const domain = domains[cursor]
                            console.log(cursor, domain)
                            await axios.post(
                                    '{{ route('tool.postAction', ['tool' => $tool->slug, 'action' => 'get-domain-authority']) }}', {
                                        domain: domain
                                    })
                                .then((res) => {
                                    if (res.data.success == true) {
                                        addResult(res.data.content)
                                    } else {
                                        ArtisanApp.toastError(res.data.content);
                                    }
                                })
                                .catch((err) => {
                                    console.log(err);
                                    ArtisanApp.toastError(err);
                                })
                            cursor++
                            if (cursor < domains.length) {
                                getReslut()
                            } else {
                                document.getElementById('loader').classList.add('d-none')
                            }
                        },
                        addResult = function(res) {
                            const status = res.country_code ? '{{ __('common.valid') }}' : '{{ __('common.invalid') }}'
                            const element = document.querySelector('#results-container')
                            const html = `<tr>
                                <td>${cursor+1}</td>
                                <td>${res.ip}</td>
                                <td>${res.da}</td>
                                <td>${res.pa}</td>
                                <td>${res.mr}</td>
                                <td>${res.linking || '-'}</td>
                            </tr>`;

                            element.innerHTML += html
                        };

                    return {
                        init: function() {
                            getReslut()
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
