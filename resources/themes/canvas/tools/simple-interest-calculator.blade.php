<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form method="post" :route="route('tool.handle', $tool->slug)">
            <div class="box-shadow mb-3 py-5">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <x-input-label>@lang('tools.currency')</x-input-label>
                            <x-text-input class="form-control" name="currency" type="currency" id="currency" required
                                :placeholder="__('tools.currency')" value="{{$results['inputs']['currency'] ?? ''}}"/>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <x-input-label>@lang('tools.startingBalance')</x-input-label>
                            <x-text-input class="form-control" name="starting_balance" type="number"
                                id="starting_balance" required :placeholder="__('tools.startingBalance')"
                                value="{{$results['inputs']['starting_balance'] ?? ''}}"/>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <x-input-label>@lang('tools.interestRate')</x-input-label>
                            <x-text-input class="form-control" name="insterest_rate" type="number" id="insterest_rate"
                                required :placeholder="__('tools.interestRate')"
                                value="{{$results['inputs']['insterest_rate'] ?? ''}}"/>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <x-input-label>@lang('tools.period')</x-input-label>
                            <select class="form-control" name="period" id="period">
                                <option value="yearly">Yearly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-4">
                            <x-input-label>@lang('tools.startDate')</x-input-label>
                            <x-text-input class="form-control" name="start_date" type="date" id="start_date" required
                                :placeholder="__('tools.startDate')"
                                value="{{$results['inputs']['start_date'] ?? ''}}"/>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group mb-4">
                            <x-input-label>@lang('tools.periodOrEnd')</x-input-label>
                            <select class="form-control" name="type" id="type">
                                <option value="period">Time Period</option>
                                <option value="date">End Date</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row" data-conditional-name="type"
                                data-conditional-value="period">
                            <div class="col-md-3">
                                <div class="form-group mb-4">
                                    <x-input-label>@lang('tools.year')</x-input-label>
                                    <x-text-input class="form-control" name="year" type="number" id="year"
                                        required :placeholder="__('tools.year')"
                                        value="{{$results['inputs']['year'] ?? ''}}"/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-4">
                                    <x-input-label>@lang('tools.month')</x-input-label>
                                    <x-text-input class="form-control" name="month" type="number" id="month"
                                        required :placeholder="__('tools.month')"
                                        value="{{$results['inputs']['month'] ?? ''}}"/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-4">
                                    <x-input-label>@lang('tools.week')</x-input-label>
                                    <x-text-input class="form-control" name="week" type="number" id="week"
                                        required :placeholder="__('tools.week')"
                                        value="{{$results['inputs']['week'] ?? ''}}"/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-4">
                                    <x-input-label>@lang('tools.day')</x-input-label>
                                    <x-text-input class="form-control" name="day" type="number" id="day"
                                        required :placeholder="__('tools.day')"
                                         value="{{$results['inputs']['day'] ?? ''}}"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-4" data-conditional-name="type"
                                data-conditional-value="date">
                            <x-input-label>@lang('tools.endDate')</x-input-label>
                            <x-text-input class="form-control" name="end_date" type="date" id="end_date" required
                                :placeholder="__('tools.endDate')"
                                value="{{$results['inputs']['end_date'] ?? ''}}"/>
                        </div>
                    </div>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill" id="calculate">
                        @lang('tools.calculate')
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
                                        <th>@lang('tools.finalBalance')</th>
                                        <th>@lang('tools.initialBalance')</th>
                                        <th>@lang('tools.interestAccrued')</th>
                                        <th>@lang('tools.monthlyIntrest')</th>
                                    </tr>
                                    <tr>
                                        <td>{{ $results['finalBalance'] . $results['inputs']['currency'] }}</td>
                                        <td>{{ $results['initialBalance'] . $results['inputs']['currency'] }}</td>
                                        <td>{{ $results['interestAccrued'] . $results['inputs']['currency'] }}</td>
                                        <td>{{ $results['monthlyIntrest'] . $results['inputs']['currency'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-12">
                            <table class="table table-style mb-0">
                                <tbody id="results-container">
                                    <tr>
                                        <th>@lang('tools.month')</th>
                                        <th>@lang('tools.interest')</th>
                                        <th>@lang('tools.totalInterest')</th>
                                        <th>@lang('tools.balance')</th>
                                    </tr>
                                    @php $month = $results['startDate'];
                                    $interest = $results['monthlyIntrest'];
                                    @endphp
                                    @for($i=0;$i < $results['months'] ; $i++)

                                    <tr>
                                        <td>{{ $month }}</td>
                                        <td>{{ $results['monthlyIntrest'] . $results['inputs']['currency'] }}</td>
                                        <td>{{ $interest }}</td>
                                        <td>{{ $results['initialBalance']  +  $interest}}</td>
                                    </tr>
                                    @php $month = \Carbon\Carbon::parse($month)->addMonth();
                                    $interest = $interest + $results['monthlyIntrest'];
                                    @endphp
                                    @endfor
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
