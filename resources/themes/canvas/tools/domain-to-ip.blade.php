<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="box-shadow my-3 py-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <h3 class="h4">@lang('tools.enterValidDomainName')</h3>
                        </div>
                        <div class="form-group">
                            <x-text-input type="url" class="form-control" name="domain" id="domain" required
                                value="{{ $results['domain'] ?? old('domain') }}" :error="$errors->has('domain')" :placeholder="__('tools.enterADomain')" />
                            <x-input-error :messages="$errors->get('domain')" class="mt-2" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <x-ad-slot :advertisement="get_advert_model('below-form')" />
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                        @lang('tools.convertToIP')
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
                            <table class="table table-style mb-0">
                                <tbody id="results-container">
                                    <tr>
                                        <th>@lang('tools.givenUrl')</th>
                                        <th>@lang('tools.serverIP')</th>
                                        <th>@lang('tools.country')</th>
                                        <th>@lang('tools.hosting')</th>
                                    </tr>
                                    <tr>
                                        <td>{{ $results['domain'] ?? '' }}</td>
                                        <td>{{ $results['content']['content']['ip'] ?? '' }}</td>
                                        <td>{{ $results['content']['content']['country'] ?? '' }}</td>
                                        <td>{{ $results['content']['content']['isp'] ?? '' }}</td>
                                    </tr>
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
</x-application-tools-wrapper>
