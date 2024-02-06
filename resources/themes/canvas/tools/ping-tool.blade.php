<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="box-shadow mb-3 py-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <h3 class="h4">@lang('tools.enterDomainNametoPing')</h3>
                        </div>
                        <div class="form-group">
                            <x-text-input class="form-control" name="domain" type="text" id="domain" required
                                value="{{ $results['domain'] ?? old('domain') }}" />
                        </div>
                        <x-input-error :messages="$errors->get('domain')" class="mt-2" />
                    </div>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                        @lang('tools.checkPing')
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
                            <table class="table table-style mb-0">
                                <thead>
                                    <th></th>
                                    <th>@lang('tools.ip')</th>
                                    <th>@lang('tools.ttl')</th>
                                    <th>@lang('tools.timeinms')</th>
                                </thead>
                                <tbody id="results-container"></tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-3" id="loader">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">@lang('common.loading')</span>
                                </div>
                            </div>
                        </div>
                        <div id="stats" class="d-none col-md-12">
                            <div class="mt-3">
                                <h4>@lang('tools.pingStatistics')</h4>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>@lang('tools.packetsTransmitted'):</strong> <span
                                            id="transmitted"></span></li>
                                    <li class="list-group-item"><strong>@lang('tools.received'):</strong> <span
                                            id="received"></span></li>
                                    <li class="list-group-item"><strong>@lang('tools.packetLoss'):</strong> <span
                                            id="loss"></span></li>
                                </ul>
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
                    const maxRequests = 4;
                    const domain = "{!! $results['domain'] !!}";
                    var cursor = 1,
                        loss = [],
                        received = [];

                    const getReslut = async function() {
                            if (cursor > maxRequests) {
                                return;
                            }
                            await axios.post(
                                    '{{ route('tool.postAction', ['tool' => $tool->slug, 'action' => 'get-domain-ping']) }}', {
                                        domain: domain
                                    })
                                .then((res) => {
                                    if (!res.data.time) {
                                        loss.push(res.data)
                                    } else {
                                        received.push(res.data)
                                    }
                                    addResult(res.data)
                                })
                                .catch((err) => {
                                    loss.push(err)
                                })
                            cursor++
                            if (cursor <= maxRequests) {
                                getReslut()
                            } else {
                                document.getElementById('loader').classList.add('d-none')
                                updateStats();
                            }
                        },
                        addResult = function(res) {
                            const status = res.country_code ? '{{ __('common.valid') }}' : '{{ __('common.invalid') }}'
                            const element = document.querySelector('#results-container')
                            const time = res.time ? `${res.time} ms` : '-'
                            const html = `<tr>
                                <td>${cursor}</td>
                                <td>${res.ip}</td>
                                <td>${res.ttl}</td>
                                <td>${time}</td>
                            </tr>`;

                            element.innerHTML += html
                        },
                        updateStats = function() {
                            document.getElementById('transmitted').innerHTML = maxRequests
                            document.getElementById('received').innerHTML = received.length
                            document.getElementById('loss').innerHTML = (100 * loss.length) / maxRequests + '%';
                            document.getElementById('stats').classList.remove('d-none')
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
