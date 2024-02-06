<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="box-shadow my-3 py-5">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label h4" for="url">@lang('tools.enterURL')</label>
                            <x-text-input type="text" class="form-control" name="url" id="url" required
                                value="{{ $results['url'] ?? old('url') }}" :placeholder="__('tools.enterWebsiteUrl')" :error="$errors->has('domain')" />
                            <x-input-error :messages="$errors->get('url')" class="mt-2" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label h4" for="country">@lang('tools.country')</label>
                            <select class="form-control" name="country" id="country">
                                @foreach ($countries as $key => $country)
                                    <option value="{{ $key }}"
                                        @if (isset($results['country']) && $results['country'] == $key) selected @endif>{{ $country }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('country')" class="mt-2" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label h4" for="keywords">@lang('tools.keywords')</label>
                                    <x-textarea-input type="text" rows="6" class="form-control" name="keywords"
                                        id="keywords" required :placeholder="__('tools.keywordsOnePerLine')" :error="$errors->has('keywords')">
                                        {{ $results['keywords'] ?? old('keywords') }}</x-textarea-input>
                                    <x-input-error :messages="$errors->get('keywords')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label h4" for="c_url_1">@lang('tools.competitorUrlOptional')</label>
                                    <x-text-input type="text" class="form-control" name="competitors[]"
                                        id="c_url_1" value="{{ $results['competitors'][0] ?? '' }}" :placeholder="__('tools.competitorUrlNum', ['number' => 1])"
                                        :error="$errors->has('competitors.0')" />
                                    <x-input-error :messages="$errors->get('competitors.0')" class="mt-2" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <x-text-input type="text" class="form-control" name="competitors[]"
                                        id="c_url_2" value="{{ $results['competitors'][1] ?? '' }}" :placeholder="__('tools.competitorUrlNum', ['number' => 2])"
                                        :error="$errors->has('competitors.1')" />
                                    <x-input-error :messages="$errors->get('competitors.1')" class="mt-2" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <x-text-input type="text" class="form-control" name="competitors[]"
                                        id="c_url_3" value="{{ $results['competitors'][2] ?? '' }}" :placeholder="__('tools.competitorUrlNum', ['number' => 3])"
                                        :error="$errors->has('competitors.2')" />
                                    <x-input-error :messages="$errors->get('competitors.2')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                        @lang('tools.getKeywordsInsights')
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
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="box-shadow">
                                <h3>@lang('tools.resultsFor')</h3>
                                {{ $results['url'] }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box-shadow">
                                <h3>@lang('tools.country')</h3>
                                {{ $countries[$results['country']] ?? 'Global' }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="progress mb-4" style="height: 3px;">
                                <div id="conversion-progress" class="progress-bar bg-success" role="progressbar"
                                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-style mb-0" id="keyword-results">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('tools.keyword')</th>
                                        <th>@lang('tools.keywordPosition')</th>
                                        <th>@lang('tools.keywordPositionMatching')</th>
                                        <th>@lang('tools.totalResults')</th>
                                        <th width="140"></th>
                                    </tr>
                                </thead>
                                <tbody id="results-container">
                                    {{-- @if ($results['content'])
                                        @foreach ($results['content'] as $key => $items)
                                            <tr @if (in_array($key, $results['keys'])) class="bg-primary" @endif>
                                                <td class="text-start ps-3">{{ $loop->iteration }}</td>
                                                <td class="text-start ps-3">
                                                    @if (isset($items['pagemap']['cse_thumbnail'][0]))
                                                        <img class="img-fluid img-thumbnail" width="50"
                                                            src="{{ $items['pagemap']['cse_thumbnail'][0]['src'] ?? '' }}" />
                                                    @endif
                                                </td>
                                                <td class="text-start ps-3">{!! $items['htmlTitle'] ?? '' !!}</td>
                                                <td class="text-start ps-3">{{ $items['link'] ?? '' }}</td>
                                                <td class="text-start ps-3">
                                                    <span
                                                        class="badge bg-{{ isset($items['cacheId']) ? 'success' : 'danger' }}">
                                                        {{ isset($items['cacheId']) ? __('common.yes') : __('common.no') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif --}}
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-3" id="loader">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">@lang('common.loading')</span>
                                </div>
                            </div>
                        </div>
                        @if (!empty($results['competitors'][0]) || !empty($results['competitors'][1]) || !empty($results['competitors'][2]))
                            <div class="col-md-12 my-4">
                                <div class="row">
                                    <div class="col-md-4 offset-md-4">
                                        <div class="form-group mb-4 text-center">
                                            <label class="h3 form-label">@lang('tools.selectCompetitorDomain')</label>
                                            <select class="form-select" name="competitors" id="competitorsSwitch">
                                                @foreach ($results['competitors'] as $competitor)
                                                    @if (!empty($competitor))
                                                        <option value="{{ $loop->iteration }}">
                                                            {{ extractHostname($competitor) }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @foreach ($results['competitors'] as $competitor)
                                    <div class="col-md-12{{ $loop->iteration == 1 ?: ' d-none' }} comptitor-wrapper"
                                        id="competitor-{{ $loop->index }}">
                                        <table class="competitor-data table table-style mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>@lang('tools.keyword')</th>
                                                    <th width="140">@lang('tools.keywordPosition')</th>
                                                </tr>
                                            </thead>
                                            <tbody data-id="{{ $loop->index }}">

                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </x-page-wrapper>
        </div>
        <x-ad-slot :advertisement="get_advert_model('below-result')" />
    @endif
    <x-tool-content :tool="$tool" />
    @if (isset($results))
        @push('page_scripts')
            <div class="modal modal-lg fade" id="urlsList" tabindex="-1" role="dialog"
                aria-labelledby="urlsListLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="urlsListLabel">@lang('tools.topRankedUrls')</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="@lang('common.close')"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('tools.urlName')</th>
                                    </tr>
                                </thead>
                                <tbody id="container-urls"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                const APP = function() {
                    const keywords = {!! $results['keywords_data'] !!};
                    const process_id = '{{ $process_id }}';
                    const country = '{{ $results['country'] }}';
                    const competitors = @json($results['competitors']);
                    const url = '{{ $results['url'] }}';
                    const result_urls = [];
                    var cursor = 0;
                    const getReslut = async function() {
                            const keyword = keywords[cursor]
                            processingNow(keyword, cursor)
                            await axios.post(
                                    '{{ route('tool.postAction', ['tool' => $tool->slug, 'action' => 'get-keyword-position']) }}', {
                                        keyword: keyword,
                                        process_id: process_id
                                    })
                                .then((res) => {
                                    updateProgress(cursor);
                                    addResult(res.data, cursor)
                                })
                                .catch((err) => {
                                    ArtisanApp.toastError(err);
                                })

                            cursor++
                            if (cursor < keywords.length) {
                                getReslut()
                            } else {
                                document.getElementById('loader').classList.add('d-none')
                            }
                        },
                        processingNow = function(keyword, index) {
                            const element = document.querySelector('#results-container');
                            const html = `<tr>
                                        <td>${index+1}</td>
                                        <td><div class="text-truncate fw-bold">${keyword}</div><span class="text-success">${url}</span></td>
                                        <td id="position-${index}"><div class="placeholder-glow"><span class="placeholder col-8"></span></div></td>
                                        <td id="matching-${index}"><div class="placeholder-glow"><span class="placeholder col-8"></span></div></td>
                                        <td id="search-volumn-${index}"><div class="placeholder-glow"><span class="placeholder col-8"></span></div></td>
                                        <td id="url-${index}"><div class="placeholder-glow"><span class="placeholder col-8"></span></div></td>
                                    </tr>`;

                            element.innerHTML += html;

                            document.querySelectorAll('.competitor-data').forEach((table, key) => {
                                const tableBody = table.querySelector('tbody')
                                const html = `<tr>
                                        <td>${index+1}</td>
                                        <td><div class="text-truncate fw-bold">${keyword}</div><span class="text-success">${competitors[tableBody.dataset.id]}</span></td>
                                        <td class="competitor-position" id="competitor-postion-${index}-${key}"><div class="placeholder-glow"><span class="placeholder col-8"></span></div></td>
                                    </tr>`;

                                tableBody.innerHTML += html;
                            });
                        },
                        updateProgress = function(cursor) {
                            var progress = (parseInt(cursor + 1) / keywords.length) * 100;
                            progress = Math.round(progress);

                            document.getElementById('conversion-progress').style.width = progress + '%'
                        },
                        initEvents = function() {
                            document.addEventListener('change', (e) => {
                                document.querySelectorAll('.comptitor-wrapper').forEach(element => {
                                    element.classList.add('d-none')
                                });
                                document.querySelector(`#competitor-${e.target.value - 1}`).classList.remove(
                                    'd-none')
                            })
                        },
                        addResult = function(res, cursor) {
                            const element = document.querySelector(`#position-${cursor}`);
                            const search_element = document.querySelector(`#search-volumn-${cursor}`);
                            const matching_element = document.querySelector(`#matching-${cursor}`);
                            const url_div = document.querySelector(`#url-${cursor}`);

                            url_div.innerHTML =
                                `<button type="button" class="btn btn-primary btn-sm show-urls-list" id="btn-url-${cursor}" data-cursor="${cursor}">
                                    {{ __('tools.showUrls') }}
                                 </button>`;
                            element.innerHTML = res.url.exact;
                            matching_element.innerHTML = res.url.matching;
                            search_element.innerHTML = res.volumn_formated;

                            document.querySelectorAll('.competitor-data').forEach((table, key) => {
                                var competitor_element = document.querySelector(
                                    `#competitor-postion-${cursor}-${key}`);
                                competitor_element.innerHTML = res.competitors[key].exact;
                            });

                            result_urls[cursor] = res.content
                            attachEvents()
                        },
                        attachEvents = function() {
                            document.querySelectorAll(`.show-urls-list:not(.event-init)`).forEach(button => {
                                button.addEventListener("click", (event) => {
                                    const index = event.target.dataset.cursor
                                    const modal = document.getElementById('urlsList')
                                    const urls = document.querySelector(`#container-urls`);
                                    var html = '';
                                    result_urls[index].forEach((result, i) => {
                                        html += `<tr>
                                                <td>${i+1}</td>
                                                <td>${result}</td>
                                            </tr>`;
                                    });
                                    urls.innerHTML = html;

                                    var urlsModal = new Modal(modal, {
                                        keyboard: false
                                    })
                                    urlsModal.show()
                                    modal.addEventListener('hidden.bs.modal', function(event) {
                                        urls.innerHTML = ''
                                    })
                                });
                            });
                        };

                    return {
                        init: function() {
                            getReslut()
                            initEvents()
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
