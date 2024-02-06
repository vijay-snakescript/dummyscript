<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="box-shadow tabbar mb-3">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <x-input-label>@lang('tools.dailyPageImpression')</x-input-label>
                            <x-text-input type="text" class="form-control" name="daily_impression" id="daily_impression"
                                required value="{{ $results['daily_impression'] ?? old('daily_impression') }}" placeholder="100"
                                :error="$errors->has('daily_impression')" />
                            <x-input-error :messages="$errors->get('daily_impression')" class="mt-2" />
                        </div>
                        <x-input-error :messages="$errors->get('daily_impression')" class="mt-2" />
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <x-input-label>@lang('tools.ctrInPercentage')</x-input-label>
                            <x-text-input type="text" class="form-control" name="ctr" id="ctr" required placeholder="1.05"
                                value="{{ $results['ctr'] ?? old('ctr') }}" :error="$errors->has('ctr')" />
                            <x-input-error :messages="$errors->get('ctr')" class="mt-2" />
                        </div>
                        <x-input-error :messages="$errors->get('ctr')" class="mt-2" />
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <x-input-label>@lang('tools.costPerClick')</x-input-label>
                            <x-text-input type="text" class="form-control" name="cost" id="cost" required placeholder="0.87"
                                value="{{ $results['cost'] ?? old('cost') }}" :error="$errors->has('cost')" />
                            <x-input-error :messages="$errors->get('cost')" class="mt-2" />
                        </div>
                        <x-input-error :messages="$errors->get('cost')" class="mt-2" />
                    </div>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row mt-4 mb-4">
                <div class="col-md-12 text-end">
                    <x-button type="submit" id="calculate" class="btn btn-outline-primary rounded-pill">
                        @lang('tools.calculateEarning')
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
                        <div class="col-md-12 result-printable">
                            <h3 class="mb-3 mt-4">@lang('tools.daily')</h3>
                            <table class="table table-style mb-0">
                                <tbody>
                                    <tr>
                                        <th width="150">@lang('tools.earnings')</th>
                                        <td><x-money :amount="$results['earning_per_day']" currency="USD" convert /></td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.clicks')</th>
                                        <td>{{ number_format($results['clicks_per_day']) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12 result-printable">
                            <h3 class="mb-3 mt-4">@lang('tools.monthly')</h3>
                            <table class="table table-style mb-0">
                                <tbody>
                                    <tr>
                                        <th width="150">@lang('tools.earnings')</th>
                                        <td><x-money :amount="$results['earning_per_month']" currency="USD" convert /></td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.clicks')</th>
                                        <td>{{ number_format($results['clicks_per_month']) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12 result-printable">
                            <h3 class="mb-3 mt-4">@lang('tools.yearly')</h3>
                            <table class="table table-style mb-0">
                                <tbody>
                                    <tr>
                                        <th width="150">@lang('tools.earnings')</th>
                                        <td><x-money :amount="$results['earning_per_year']" currency="USD" convert /></td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.clicks')</th>
                                        <td>{{ number_format($results['clicks_per_year']) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </x-page-wrapper>
        </div>
    @endif
    <x-tool-content :tool="$tool" />
</x-application-tools-wrapper>
