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
                        @lang('tools.checkDomainName')
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
                                        <td class="text-start ps-3">{{ $results['info']->domainName ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.hosting')</th>
                                        <td class="text-start ps-3">
                                            {{ $results['content']['content']['isp'] ?? '' }}</td>
                                    </tr>
                                    @if (isset($results['info']->registrar) && !empty($results['info']->registrar))
                                    <tr>
                                        <th>@lang('tools.registrar')</th>
                                        <td class="text-start ps-3">{{ $results['info']->registrar ?? '' }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>@lang('tools.serverIP')</th>
                                        <td class="text-start ps-3">{{ $results['content']['content']['ip'] ?? '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.nameServers')</th>
                                        <td class="text-start ps-3">
                                            {{ implode(', ', $results['info']->nameServers) }}</td>
                                    </tr>
                                    @if (isset($results['info']->creationDate) && !empty($results['info']->creationDate))
                                        <tr>
                                            <th>@lang('tools.createdOnDate')</th>
                                            <td class="text-start ps-3">
                                                {{ date('Y-m-d', $results['info']->creationDate) ?? '' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($results['info']->expirationDate) && !empty($results['info']->expirationDate))
                                        <tr>
                                            <th>@lang('tools.expirationDate')</th>
                                            <td class="text-start ps-3">
                                                {{ date('Y-m-d', $results['info']->expirationDate) ?? '' }}
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th>@lang('tools.updationDate')</th>
                                        <td class="text-start ps-3">
                                            {{ date('Y-m-d', $results['info']->updatedDate) ?? '' }}</td>
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
