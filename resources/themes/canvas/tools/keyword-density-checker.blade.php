<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-tool-property-display :tool="$tool" name="wc_tool" :label="null" :plans="true" />
        <div class="panel-left-generator box-shadow my-3 py-5">
            <x-form method="post" :route="route('tool.handle', $tool->slug)">
                <input id="type" type="hidden" name="type" value="{{ $type }}">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="nav nav-tabs justify-content-center mb-3" id="density-tabs">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="tab-density-url" data-bs-toggle="tab"
                                    data-bs-target="#density-url" data-type="1" type="button" role="tab"
                                    aria-controls="density-url" aria-selected="false">@lang('admin.itemURL')</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-density-text" data-bs-toggle="tab"
                                    data-bs-target="#density-text" data-type="2" type="button" role="tab"
                                    aria-controls="density-text" aria-selected="false">@lang('tools.text')</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="density-url" role="tabpanel"
                                aria-labelledby="density-url-tab">
                                <div class="form-group mb-3">
                                    <x-input-label>@lang('tools.enterURL')</x-input-label>
                                    <x-text-input type="text" class="form-control" name="url" id="url"
                                        value="{{ $results['url'] ?? old('url') }}" :error="$errors->has('url')"
                                        :placeholder="__('tools.enterWebsiteUrl')" />
                                    <x-input-error :messages="$errors->get('url')" class="mt-2" />
                                </div>
                            </div>
                            <div class="tab-pane" id="density-text" role="tabpanel" aria-labelledby="density-text-tab">
                                <div class="form-group mb-3">
                                    <x-textarea-input type="text" name="content" class="form-control"
                                        :placeholder="__('common.someText')" id="textarea" rows="8">
                                        {{ $results['content'] ?? '' }}
                                    </x-textarea-input>
                                    <x-input-error :messages="$errors->get('content')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <x-ad-slot :advertisement="get_advert_model('below-form')" />
                <div class="row">
                    <div class="col-md-12 text-end">
                        <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                            @lang('tools.exploreKeywordDensity')
                        </x-button>
                    </div>
                </div>
            </x-form>
        </div>
    </x-tool-wrapper>
    @if (isset($results))
        <div class="tool-results-wrapper">
            <x-ad-slot :advertisement="get_advert_model('above-result')" />
            <x-page-wrapper :title="__('common.result')">
                <div class="result">
                    <div class="row my-4">
                        <div class="col-md-4">
                            <div class="box-shadow">
                                <h4>@lang('tools.urlToVerify')</h4>
                                {{ $results['url'] }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box-shadow">
                                <h4>@lang('tools.loadTime')</h4>
                                {{ round($results['loadtime'], 2) }}
                                {{ trans_choice('tools.inSeconds', ['count' => round($results['loadtime'], 2)]) }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box-shadow">
                                <h4>@lang('tools.totalKeywords')</h4>
                                {{ $results['total_keywords'] }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h3>@lang('tools.topKeywords')</h3>
                        </div>
                        <div class="col-md-12 table-responsive">
                            <table class="table table-style mb-0">
                                <thead>
                                    <tr>
                                        <th>@lang('tools.keyword')</th>
                                        <th width="80">@lang('tools.frequency')</th>
                                        @if ($type == 1)
                                            <th width="60" class="text-center">@lang('seo.title')</th>
                                            <th width="95" class="text-center">@lang('seo.description')</th>
                                            <th width="80" class="text-center">@lang('seo.headings')</th>
                                        @endif
                                        <th width="80">@lang('tools.density')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($results['top'] as $top)
                                        <tr>
                                            <td class="text-start ps-3">
                                                {{ $top['keyword'] }}
                                            </td>
                                            <td class="text-start ps-3">
                                                {{ $top['frequency'] }}
                                            </td>
                                            @if ($type == 1)
                                                <td class="text-center">{!! $top['title'] ? '<i class="an an-chack text-success"></i>' : '<i class="an an-times text-danger"></i>' !!}</td>
                                                <td class="text-center">{!! $top['description'] ? '<i class="an an-chack text-success"></i>' : '<i class="an an-times text-danger"></i>' !!}</td>
                                                <td class="text-center">{!! $top['headers'] ? '<i class="an an-chack text-success"></i>' : '<i class="an an-times text-danger"></i>' !!}</td>
                                            @endif
                                            <td class="text-start ps-3">
                                                {{ round(($top['frequency'] / $results['total_keywords']) * 100, 2) }} %
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row my-4">
                        <div class="col-md-12 text-center">
                            <h3>@lang('tools.keywordDensity')</h3>
                        </div>
                        <div class="col-md-3 mt-3">
                            <ul class="nav nav-pills flex-column" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="oneword-tab" data-bs-toggle="tab"
                                        data-bs-target="#oneword" href="#oneword" role="tab"
                                        aria-controls="oneword" aria-selected="true">@lang('tools.oneWord')</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="twoword-tab" data-bs-toggle="tab"
                                        data-bs-target="#twoword" href="#twoword" role="tab"
                                        aria-controls="twoword" aria-selected="false">@lang('tools.twoWord')</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="threeword-tab" data-bs-toggle="tab"
                                        data-bs-target="#threeword" href="#threeword" role="tab"
                                        aria-controls="threeword" aria-selected="false">@lang('tools.threeWord')</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="fourword-tab" data-bs-toggle="tab"
                                        data-bs-target="#fourword" href="#fourword" role="tab"
                                        aria-controls="fourword" aria-selected="false">@lang('tools.fourWord')</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-9 table-responsive">
                            <div class="tab-content">
                                <div class="tab-pane active" id="oneword" role="tabpanel"
                                    aria-labelledby="oneword-tab">
                                    <table class="table table-style mb-0">
                                        <thead>
                                            <tr>
                                                <th>@lang('tools.keyword')</th>
                                                <th width="80">@lang('tools.frequency')</th>
                                                @if ($type == 1)
                                                    <th width="60" class="text-center">@lang('seo.title')</th>
                                                    <th width="95" class="text-center">@lang('seo.description')</th>
                                                    <th width="80" class="text-center">@lang('seo.headings')</th>
                                                @endif
                                                <th width="80">@lang('tools.density')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($results['one_word_count'] as $one_word)
                                                <tr>
                                                    <td class="text-start ps-3">
                                                        {{ $one_word['keyword'] }}
                                                    </td>
                                                    <td class="text-start ps-3">
                                                        {{ $one_word['frequency'] }}
                                                    </td>
                                                    @if ($type == 1)
                                                        <td class="text-center">{!! $one_word['title'] ? '<i class="an an-chack text-success"></i>' : '<i class="an an-times text-danger"></i>' !!}</td>
                                                        <td class="text-center">{!! $one_word['description']
                                                            ? '<i class="an an-chack text-success"></i>'
                                                            : '<i class="an an-times text-danger"></i>' !!}</td>
                                                        <td class="text-center">{!! $one_word['headers']
                                                            ? '<i class="an an-chack text-success"></i>'
                                                            : '<i class="an an-times text-danger"></i>' !!}</td>
                                                    @endif
                                                    <td class="text-start ps-3">
                                                        {{ round(($one_word['frequency'] / $results['total_keywords']) * 100, 2) }}
                                                        %
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="twoword" role="tabpanel" aria-labelledby="twoword-tab">
                                    <table class="table table-style mb-0">
                                        <thead>
                                            <tr>
                                                <th>@lang('tools.keyword')</th>
                                                <th width="80">@lang('tools.frequency')</th>
                                                @if ($type == 1)
                                                    <th width="60" class="text-center">@lang('seo.title')</th>
                                                    <th width="95" class="text-center">@lang('seo.description')</th>
                                                    <th width="80" class="text-center">@lang('seo.headings')</th>
                                                @endif
                                                <th width="80">@lang('tools.density')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($results['two_word_count'] as $two_word)
                                                <tr>
                                                    <td class="text-start ps-3">
                                                        {{ $two_word['keyword'] }}
                                                    </td>
                                                    <td class="text-start ps-3">
                                                        {{ $two_word['frequency'] }}
                                                    </td>
                                                    @if ($type == 1)
                                                        <td class="text-center">{!! $two_word['title'] ? '<i class="an an-chack text-success"></i>' : '<i class="an an-times text-danger"></i>' !!}</td>
                                                        <td class="text-center">{!! $two_word['description']
                                                            ? '<i class="an an-chack text-success"></i>'
                                                            : '<i class="an an-times text-danger"></i>' !!}</td>
                                                        <td class="text-center">{!! $two_word['headers']
                                                            ? '<i class="an an-chack text-success"></i>'
                                                            : '<i class="an an-times text-danger"></i>' !!}</td>
                                                    @endif
                                                    <td class="text-start ps-3">
                                                        {{ round(($two_word['frequency'] / $results['total_keywords']) * 100, 2) }}
                                                        %
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="threeword" role="tabpanel"
                                    aria-labelledby="threeword-tab">
                                    <table class="table table-style mb-0">
                                        <thead>
                                            <tr>
                                                <th>@lang('tools.keyword')</th>
                                                <th width="80">@lang('tools.frequency')</th>
                                                @if ($type == 1)
                                                    <th width="60" class="text-center">@lang('seo.title')</th>
                                                    <th width="95" class="text-center">@lang('seo.description')</th>
                                                    <th width="80" class="text-center">@lang('seo.headings')</th>
                                                @endif
                                                <th width="80">@lang('tools.density')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($results['three_word_count'] as $three_word)
                                                <tr>
                                                    <td class="text-start ps-3">
                                                        {{ $three_word['keyword'] }}
                                                    </td>
                                                    <td class="text-start ps-3">
                                                        {{ $three_word['frequency'] }}
                                                    </td>
                                                    @if ($type == 1)
                                                        <td class="text-center">{!! $three_word['title']
                                                            ? '<i class="an an-chack text-success"></i>'
                                                            : '<i class="an an-times text-danger"></i>' !!}</td>
                                                        <td class="text-center">{!! $three_word['description']
                                                            ? '<i class="an an-chack text-success"></i>'
                                                            : '<i class="an an-times text-danger"></i>' !!}</td>
                                                        <td class="text-center">{!! $three_word['headers']
                                                            ? '<i class="an an-chack text-success"></i>'
                                                            : '<i class="an an-times text-danger"></i>' !!}</td>
                                                    @endif
                                                    <td class="text-start ps-3">
                                                        {{ round(($three_word['frequency'] / $results['total_keywords']) * 100, 2) }}
                                                        %
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="fourword" role="tabpanel" aria-labelledby="fourword-tab">
                                    <table class="table table-style mb-0">
                                        <thead>
                                            <tr>
                                                <th>@lang('tools.keyword')</th>
                                                <th width="80">@lang('tools.frequency')</th>
                                                @if ($type == 1)
                                                    <th width="60" class="text-center">@lang('seo.title')</th>
                                                    <th width="95" class="text-center">@lang('seo.description')</th>
                                                    <th width="80" class="text-center">@lang('seo.headings')</th>
                                                @endif
                                                <th width="80">@lang('tools.density')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($results['four_word_count'] as $four_word)
                                                <tr>
                                                    <td class="text-start ps-3">
                                                        {{ $four_word['keyword'] }}
                                                    </td>
                                                    <td class="text-start ps-3">
                                                        {{ $four_word['frequency'] }}
                                                    </td>
                                                    @if ($type == 1)
                                                        <td class="text-center">{!! $four_word['title'] ? '<i class="an an-chack text-success"></i>' : '<i class="an an-times text-danger"></i>' !!}</td>
                                                        <td class="text-center">{!! $four_word['description']
                                                            ? '<i class="an an-chack text-success"></i>'
                                                            : '<i class="an an-times text-danger"></i>' !!}</td>
                                                        <td class="text-center">{!! $four_word['headers']
                                                            ? '<i class="an an-chack text-success"></i>'
                                                            : '<i class="an an-times text-danger"></i>' !!}</td>
                                                    @endif
                                                    <td class="text-start ps-3">
                                                        {{ round(($four_word['frequency'] / $results['total_keywords']) * 100, 2) }}
                                                        %
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
    @push('page_scripts')
        <script>
            const App = function() {
                const typeField = document.querySelector('#type')
                const initEvents = function() {
                    var tabEl = document.querySelector('#density-tabs')
                    tabEl.addEventListener('shown.bs.tab', function(event) {
                        typeField.value = event.target.dataset.type
                    })
                }

                return {
                    init: function() {
                        initEvents();
                    }
                }
            }();
            document.addEventListener("DOMContentLoaded", function(event) {
                App.init();
            });
        </script>
    @endpush
</x-application-tools-wrapper>
