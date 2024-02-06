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
                                value="{{ $results['domain'] ?? old('domain') }}" :placeholder="__('tools.enterADomain')" :error="$errors->has('domain')" />
                            <x-input-error :messages="$errors->get('domain')" class="mt-2" />
                        </div>
                    </div>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill">
                        @lang('tools.checkCompression')
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
                        <div class="col-md-8">
                            <table class="table table-style mb-0">
                                <tbody id="results-container">
                                    <tr>
                                        <th>@lang('tools.givenUrl')</th>
                                        <td class="text-start ps-3">{{ $results['domain'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.gZip')</th>
                                        <td class="text-start ps-3">
                                            @if (isset($results['content']['encoding'][0]) && $results['content']['encoding'][0] == 'gzip')
                                                @lang('tools.gzipEnabled')
                                            @else
                                                @lang('tools.gzipdisabled')
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.compressedSize')</th>
                                        <td class="text-start ps-3">
                                            {{ formatSizeUnits($results['content']['pagesize_compressed'] ?? 0) }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.uncompressedSize')</th>
                                        <td class="text-start ps-3">
                                            {{ formatSizeUnits($results['content']['pagesize_uncompressed'] ?? 0) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.saving')</th>
                                        <td class="text-start ps-3">
                                            {{ formatSizeUnits($results['content']['saving'] ?? 0) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <div class="progress-wrap mt-4">
                                <div class="progress-value">
                                    <div>{{ $results['content']['compression'] ?? 0 }}<br>
                                        <span class="d-block mt-2">%</span>
                                    </div>
                                </div>
                                <svg data-percentage="{{ $results['content']['compression'] ?? 0 }}"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="-1 -1 34 34">
                                    <circle cx="16" cy="16" r="15.9" class="circle" />
                                    <circle cx="16" cy="16" r="15.9"
                                        class="progress progress-{{ $results['content']['compression'] ?? 0 >= 75 ? 'success' : ($results['content']['compression'] ?? 0 > 60 ? 'warning' : 'danger') }}"
                                        style="stroke-dashoffset: {{ 100 - $results['content']['compression'] ?? 0 }}px;" />
                                </svg>
                            </div>
                        </div>
                        <div class="col-md-12 mt-4">
                            <h3 class="text-center mb-3">@lang('tools.httpHeaders')</h3>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-style mb-0">
                                <tbody id="results-container">
                                    <tr>
                                        <th width="200">@lang('tools.urlStatus')</th>
                                        <td class="text-start ps-3">HTTP/{{ $results['content']['protocol'] ?? '' }}
                                            {{ $results['content']['status_code'] ?? '' }}</td>
                                    </tr>
                                    @if (isset($results['content']['headers']['Date'][0]))
                                        <tr>
                                            <th>@lang('tools.date')</th>
                                            <td class="text-start ps-3">
                                                {{ $results['content']['headers']['Date'][0] ?? '' }}</td>
                                        </tr>
                                    @endif
                                    @if (isset($results['content']['headers']['Content-Type'][0]))
                                        <tr>
                                            <th>@lang('tools.contentType')</th>
                                            <td class="text-start ps-3">
                                                {{ $results['content']['headers']['Content-Type'][0] ?? '' }}</td>
                                        </tr>
                                    @endif
                                    @if (isset($results['content']['headers']['Transfer-Encoding'][0]))
                                        <tr>
                                            <th>@lang('tools.transferEncoding')</th>
                                            <td class="text-start ps-3">
                                                {{ $results['content']['headers']['Transfer-Encoding'][0] ?? '' }}</td>
                                        </tr>
                                    @endif
                                    @if (isset($results['content']['headers']['Connection'][0]))
                                        <tr>
                                            <th>@lang('tools.connection')</th>
                                            <td class="text-start ps-3">
                                                {{ $results['content']['headers']['Connection'][0] ?? '' }}</td>
                                        </tr>
                                    @endif
                                    @if (isset($results['content']['headers']['Cache-Control'][0]))
                                        <tr>
                                            <th>@lang('tools.cacheControl')</th>
                                            <td class="text-start ps-3">
                                                {{ $results['content']['headers']['Cache-Control'][0] ?? '' }}</td>
                                        </tr>
                                    @endif
                                    @if (isset($results['content']['headers']['Expires'][0]))
                                        <tr>
                                            <th>@lang('tools.expires')</th>
                                            <td class="text-start ps-3">
                                                {{ $results['content']['headers']['Expires'][0] ?? '' }}</td>
                                        </tr>
                                    @endif
                                    @if (isset($results['content']['headers']['Vary'][0]))
                                        <tr>
                                            <th>@lang('tools.vary')</th>
                                            <td class="text-start ps-3">
                                                {{ $results['content']['headers']['Vary'][0] ?? '' }}</td>
                                        </tr>
                                    @endif
                                    @if (isset($results['content']['headers']['Server'][0]))
                                        <tr>
                                            <th>@lang('tools.server')</th>
                                            <td class="text-start ps-3">
                                                {{ $results['content']['headers']['Server'][0] ?? '' }}</td>
                                        </tr>
                                    @endif
                                    @if (isset($results['content']['headers']['CF-RAY'][0]))
                                        <tr>
                                            <th>@lang('tools.cfRay')</th>
                                            <td class="text-start ps-3">
                                                {{ $results['content']['headers']['CF-RAY'][0] ?? '' }}</td>
                                        </tr>
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
</x-application-tools-wrapper>
