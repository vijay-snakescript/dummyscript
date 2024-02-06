<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="box-shadow my-3 py-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <h3 class="h4">@lang('tools.enterKeyword')</h3>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <x-text-input rows="5" class="form-control" name="keyword" id="keyword" required
                                value="{{ $results['keyword'] ?? old('keyword') }}" :placeholder="__('tools.enterAKeyword')" />
                            <x-input-error :messages="$errors->get('keyword')" />
                        </div>
                    </div>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                        @lang('tools.findRelatedKeywords')
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
                            <div class="progress mb-4" style="height: 3px;">
                                <div id="conversion-progress" class="progress-bar bg-success" role="progressbar"
                                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <h3>@lang('tools.suggestions'): <span id="count-div">{{ $results['count'] }}</span></h3>
                            <table class="table table-style mb-0" id="tableID">
                                <thead>
                                    <th>@lang('tools.keywords')</th>
                                    <th class="text-center" width="150">@lang('tools.wordCount')</th>
                                    <th class="text-center" width="150">@lang('tools.keywordLength')</th>
                                </thead>
                                <tbody id="suggestions-container">
                                    @if (!$results['has_suggestions'])
                                        <tr>
                                            <td class="text-center" colspan="4">@lang('tools.noSuggestions')</td>
                                        </tr>
                                    @endif
                                    @if ($results['has_suggestions'])
                                        @foreach ($results['suggestions'] as $suggestion)
                                            <tr>
                                                <td>{{ $suggestion }}</td>
                                                <td class="text-center">{{ get_number_of_words_in_text($suggestion) }}
                                                </td>
                                                <td class="text-center">{{ strlen($suggestion) }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
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
        <x-ad-slot :advertisement="get_advert_model('below-result')" />
    @endif
    <x-tool-content :tool="$tool" />
    @if (isset($results))
        @push('page_scripts')
            <script>
                const APP = function() {
                    const search_keywords = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l'];
                    var count = "{{ $results['count'] }}";
                    const getReslut = async function(element, cursor, keywords) {
                            if (keywords.length == cursor) {
                                return;
                            }
                            const keyword = keywords[cursor]
                            await axios.post(
                                    '{{ route('tool.postAction', ['tool' => $tool->slug, 'action' => 'get-keyword-name-search']) }}', {
                                        keyword: "{{ $results['keyword'] }}" + ' ' + keyword
                                    })
                                .then((res) => {
                                    updateProgress(cursor);
                                    updateResult(element, cursor, res.data)
                                })
                                .catch((err) => {
                                    resultError(element, cursor)
                                })
                            cursor++
                            if (cursor < keywords.length) {
                                getReslut(element, cursor, keywords)
                            }
                        },
                        updateResult = function(element, cursor, res) {
                            for (var i = 0; i < res.length; i++) {
                                var html = `<tr>
                                                <td>${res[i]}</td>
                                                <td class="text-center">${res[i].trim().split(/\s+/).length}</td>
                                                <td class="text-center">${res[i].length}</td>
                                            </tr>`;
                                document.querySelector(`#suggestions-container`).innerHTML += html
                            }
                            document.getElementById("count-div").innerHTML = (parseInt(count) + parseInt(res.length));
                            count = parseInt(count) + parseInt(res.length);
                        },
                        updateProgress = function(cursor) {
                            var progress = (parseInt(cursor + 1) / search_keywords.length) * 100;
                            progress = Math.round(progress);

                            document.getElementById('conversion-progress').style.width = progress + '%'
                            if ((cursor + 1) == search_keywords.length) {
                                document.querySelector(`#loader`).classList.add('d-none');
                            }
                        },
                        resultError = function(element, cursor) {
                            const status = `<span class="text-danger">{{ __('common.error') }}</span>`
                            updateResult(element, cursor, {
                                status: status
                            })
                        };

                    return {
                        init: function() {
                            getReslut('suggestions-container', 0, search_keywords)
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
