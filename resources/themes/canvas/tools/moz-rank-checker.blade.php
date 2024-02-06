<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="box-shadow my-3 py-5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <h3 class="h4">@lang('tools.enterURL')</h3>
                        </div>
                        <div class="form-group">
                            <x-text-input type="text" class="form-control" name="domain" id="domain" required
                                value="{{ $results['domain'] ?? old('domain') }}" :placeholder="__('tools.enterOrPasteUrl')" :error="$errors->has('domain')" />
                            <x-input-error :messages="$errors->get('domain')" class="mt-2" />
                        </div>
                    </div>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                        @lang('tools.checkMozRank')
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
                                        <td class="text-start ps-3">{{ $results['domain'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.domainName')</th>
                                        <td class="text-start ps-3">
                                            {{ extractHostname($results['content']['ip'] ?? '', true) }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.da')</th>
                                        <td class="text-start ps-3">{{ $results['content']['da'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.pa')</th>
                                        <td class="text-start ps-3">{{ $results['content']['pa'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.mozRank')</th>
                                        <td class="text-start ps-3">{{ $results['content']['mr'] ?? '' }}
                                            (<a target="_blank"
                                                href="https://www.google.com/search?q=site:{{ $results['domain'] }}">@lang('tools.googleIndex')</a>
                                            , <a target="_blank"
                                                href="https://webcache.googleusercontent.com/search?q=cache:{{ $results['domain'] }}">@lang('tools.googleCache')</a>)
                                        </td>
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
