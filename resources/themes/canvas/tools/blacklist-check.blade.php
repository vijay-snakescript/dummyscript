<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="box-shadow my-3 py-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <h3 class="h4">@lang('tools.enterDomainName')</h3>
                        </div>
                        <div class="form-group">
                            <x-text-input type="text" class="form-control" name="domain" id="domain" required
                                value="{{ $results['domain'] ?? old('domain') }}" :error="$errors->has('domain')" :placeholder="__('tools.enterADomain')" />
                            <x-input-error :messages="$errors->get('domain')" class="mt-2" />
                        </div>
                    </div>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                        @lang('tools.checkBlacklist')
                    </x-button>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    @if (isset($results))
        <div class="tool-results-wrapper">
            <x-ad-slot :advertisement="get_advert_model('above-result')" />
            <x-page-wrapper :title="__('common.result')">
                <div class="result mt-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="progress mb-3" style="height: 3px;">
                                <div id="conversion-progress" class="progress-bar bg-success" role="progressbar"
                                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <table class="table table-style mb-0">
                                <tr>
                                    <th></th>
                                    <th>@lang('tools.host')</th>
                                    <th>@lang('tools.status')</th>
                                </tr>
                                <tbody id="results-container">

                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-3 loader-search" id="loader">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">@lang('common.loading')</span>
                                </div>
                            </div>
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
                    const search_ip = "{{ $results['domainIp'] }}";
                    var cursor = 0;
                    const getReslut = async function() {
                            if (domains.length == cursor) {
                                return;
                            }
                            const domain = domains[cursor]
                            initRequest()
                            await axios.post(
                                    '{{ route('tool.postAction', ['tool' => $tool->slug, 'action' => 'get-domain-authority']) }}', {
                                        domain: domain,
                                        search_ip: search_ip
                                    })
                                .then((res) => {
                                    addResult(res.data)
                                })
                                .catch((err) => {
                                    resultError()
                                })
                            cursor++
                            if (cursor < domains.length) {
                                getReslut()
                            } else {
                                document.getElementById('loader').classList.add('d-none')
                            }
                        },
                        updateProgress = function() {
                            var progress = (parseInt(cursor + 1) / domains.length) * 100;
                            progress = Math.round(progress);

                            document.getElementById('conversion-progress').style.width = progress + '%'
                        },
                        initRequest = function() {
                            const element = document.querySelector('#results-container')
                            const html = `<tr>
                                <td>${cursor+1}</td>
                                <td>${domains[cursor]}</td>
                                <td class="blacklist-status-${cursor}"><div class="placeholder-glow"><span class="placeholder col-4"></span></div></td>
                            </tr>`;

                            element.innerHTML += html
                        },
                        resultError = function() {
                            addResult({
                                status: '<span>{{ __('common.error') }}</span>'
                            })
                        },
                        addResult = function(res) {
                            updateProgress()
                            document.querySelector(`#results-container .blacklist-status-${cursor}`).innerHTML = res.status
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
