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
                        @lang('tools.examineSSL')
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
                                        <th width="200">@lang('tools.givenUrl')</th>
                                        <td class="text-start ps-3">{{ $results['domain'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.sslCertificate')</th>
                                        <td class="text-start ps-3">
                                            @if ($results['content']['is_valid'])
                                                @lang('tools.validSSl')
                                            @else
                                                @lang('tools.invalidSSl')
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.issuer')</th>
                                        <td class="text-start ps-3">{{ $results['content']['issuer'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.expiresAt')</th>
                                        <td class="text-start ps-3">
                                            {{ $results['content']['expire_at']->diffForHumans(['parts' => 3]) ?? '' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12 mt-4 text-center">
                            <h3 class="mb-3">@lang('tools.server')</h3>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-style mb-0">
                                <tbody id="results-container1">
                                    <tr>
                                        <th width="200">@lang('tools.cName')</th>
                                        <td class="text-start ps-3">
                                            {{ $results['content']['certInfo']['subject']['CN'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.valid')</th>
                                        <td class="text-start ps-3">
                                            {{ __('tools.serverDate', ['from' => $results['content']['parse_result']['server_from_date'] ?? '', 'todate' => $results['content']['parse_result']['server_to_date'] ?? '']) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.sans')</th>
                                        <td class="text-start ps-3">
                                            {{ $results['content']['parse_result']['sans'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.signatureAlgorithm')</th>
                                        <td class="text-start ps-3">
                                            {{ $results['content']['certInfo']['signatureTypeLN'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.issuer')</th>
                                        <td class="text-start ps-3">
                                            {{ $results['content']['certInfo']['issuer']['O'] ?? '' }}</td>
                                    </tr>
                                    @if (!empty($results['content']['cert_chain']))
                                        <tr>
                                            <th>@lang('tools.certificate')</th>
                                            <td class="text-start ps-3">{!! nl2br($results['content']['cert_chain']) !!}</td>
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
