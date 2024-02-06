<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="box-shadow my-3 py-5">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <h3 class="h4">@lang('tools.enterKeyword')</h3>
                        </div>
                        <div class="form-group">
                            <x-text-input type="text" class="form-control" name="keyword" id="keyword" required
                                value="{{ $results['keyword'] ?? old('keyword') }}" :placeholder="__('tools.enterKeyword')"
                                :error="$errors->has('domain')" />
                            <x-input-error :messages="$errors->get('keyword')" class="mt-2" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <h3 class="h4">@lang('tools.country')</h3>
                        </div>
                        <div class="form-group">
                            <select class="form-select" name="country" id="country">
                                @foreach ($countries as $key => $country)
                                    <option value="{{ $key }}"
                                        @if (isset($results['country']) && $results['country'] == $key) selected @endif>{{ $country }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('country')" class="mt-2" />
                        </div>
                    </div>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill px-4">
                        @lang('common.search')
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
                    <div class="row my-4">
                        <div class="col-md-8 col-sm-12">
                            <h3>@lang('tools.searchTrends')</h3>
                            <iframe src="{{ $results['trends'] }}" width="100%" height="300"
                                frameborder="0"></iframe>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-shadow">
                                        <h3>@lang('tools.searchKeyword')</h3>
                                        {{ $results['keyword'] }}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="box-shadow">
                                        <h3>@lang('tools.country')</h3>
                                        {{ $countries[$results['country']] ?? 'Global' }}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="box-shadow">
                                        <h3>@lang('tools.totalResults')</h3>
                                        {{ $results['total_results_formated'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-style mb-0" id="serp-results">
                                <thead>
                                    <tr>
                                        <th data-sort="false">#</th>
                                        <th data-sort="false">@lang('tools.thumbnail')</th>
                                        <th>@lang('tools.title')</th>
                                        <th>@lang('tools.description')</th>
                                        <th data-sort="false">@lang('tools.cached')</th>
                                        <th data-sort="false" width="100"></th>
                                    </tr>
                                </thead>
                                <tbody id="results-container">
                                    @if (isset($results['content']['items']))
                                        @foreach ($results['content']['items'] as $item)
                                            <tr>
                                                <td class="text-start ps-3">{{ $loop->iteration }}</td>
                                                <td class="text-start ps-3">
                                                    @if (isset($item['pagemap']['cse_thumbnail'][0]))
                                                        <img class="img-fluid img-thumbnail" width="50"
                                                            src="{{ $item['pagemap']['cse_thumbnail'][0]['src'] ?? '' }}" />
                                                    @endif
                                                </td>
                                                <td class="text-start ps-3">
                                                    <div>{!! $item['htmlTitle'] ?? ($item['title'] ?? '') !!}</div>
                                                    <span class="text-success">{{ $item['displayLink'] ?? '' }}</span>
                                                </td>
                                                <td class="text-start ps-3">
                                                    {!! $item['htmlSnippet'] ?? ($item['snippet'] ?? '') !!}
                                                </td>
                                                <td class="text-start ps-3">
                                                    <span
                                                        class="badge bg-{{ !empty($item['cacheId']) ? 'success' : 'danger' }}">
                                                        {{ !empty($item['cacheId']) ? __('common.yes') : __('common.no') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ $item['link'] ?? '' }}" target="_blank"
                                                        rel="nofollow noreferrer noopener"
                                                        class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                                                        title="@lang('admin.visitSite')">
                                                        <i class="an an-link"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </x-page-wrapper>
        </div>
        <x-ad-slot :advertisement="get_advert_model('below-result')" />
    @endif
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
        <script>
            const APP = function() {
                return {
                    init: function() {
                        new simpleDatatables.DataTable('#serp-results', {
                            classes: {
                                input: 'form-control',
                                // selector: 'datatable-selector form-select d-inline-block w-auto',
                            }
                        });
                    },
                }
            }()
            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
    @endpush
</x-application-tools-wrapper>
