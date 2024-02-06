<div class="container-fluid bg-light dark-mode-light-bg py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <x-form id="frmAgeCalc" class="no-app-loader" method="post" :route="route('front.index.action')">
                    <div class="box-shadow tabbar bg-white">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>@lang('tools.birthDate')</h3>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <x-input-label>@lang('tools.year')</x-input-label>
                                    <select name="birth_year" id="year" required
                                        class="form-control form-select"></select>
                                </div>
                                <x-input-error :messages="$errors->get('birth_year')" class="mt-2" />
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <x-input-label>@lang('tools.month')</x-input-label>
                                    <select name="birth_month" id="month" required
                                        class="form-control form-select"></select>
                                </div>
                                <x-input-error :messages="$errors->get('birth_month')" class="mt-2" />
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <x-input-label>@lang('tools.day')</x-input-label>
                                    <select name="birth_day" id="day" required
                                        class="form-control form-select"></select>
                                </div>
                                <x-input-error :messages="$errors->get('birth_day')" class="mt-2" />
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h3>@lang('tools.dateFrom')</h3>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <x-input-label>@lang('tools.year')</x-input-label>
                                    <select name="from_year" id="from_year" required
                                        class="form-control form-select"></select>
                                </div>
                                <x-input-error :messages="$errors->get('from_year')" class="mt-2" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <x-input-label>@lang('tools.month')</x-input-label>
                                    <select name="from_month" id="from_month" required
                                        class="form-control form-select"></select>
                                </div>
                                <x-input-error :messages="$errors->get('from_month')" class="mt-2" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <x-input-label>@lang('tools.day')</x-input-label>
                                    <select name="from_day" id="from_day" required
                                        class="form-control form-select"></select>
                                </div>
                                <x-input-error :messages="$errors->get('from_day')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12 text-end">
                            <x-button type="submit" id="btnSubmit" class="btn btn-primary rounded-pill">
                                @lang('tools.calculateAge')
                            </x-button>
                        </div>
                    </div>
                </x-form>
            </div>
        </div>
        @if (isset($results))
            <x-page-wrapper :title="__('common.result')" class="tool-age-calculator-results tool-results-wrapper">
                <div class="result mt-4">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="h2 mb-4" id="add_in_years">{{ $results['years'] }}</h3>
                        </div>
                        <div class="col-md-12 result-printable">
                            <table class="table table-style mb-0">
                                <tbody>
                                    <tr>
                                        <th>@lang('tools.currentAge')</th>
                                        <td>
                                            <div class="text-break" id="age">{{ $results['current'] }}</div>
                                        </td>
                                        <td class="d-print-none">
                                            <x-copy-target target="age" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.ageInMonths')</th>
                                        <td>
                                            <div class="text-break" id="age_months">{{ $results['months'] }}</div>
                                        </td>
                                        <td class="d-print-none">
                                            <x-copy-target target="age_months" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.ageInWeeks')</th>
                                        <td>
                                            <div class="text-break" id="age_weeks">{{ $results['weeks'] }}</div>
                                        </td>
                                        <td class="d-print-none">
                                            <x-copy-target target="age_weeks" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.ageInDays')</th>
                                        <td>
                                            <div class="text-break" id="age_days">{{ $results['days'] }}</div>
                                        </td>
                                        <td class="d-print-none">
                                            <x-copy-target target="age_days" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.ageInHours')</th>
                                        <td>
                                            <div class="text-break" id="age_hours">{{ $results['hours'] }}</div>
                                        </td>
                                        <td class="d-print-none">
                                            <x-copy-target target="age_hours" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.ageInMin')</th>
                                        <td>
                                            <div class="text-break" id="age_min">{{ $results['minutes'] }}</div>
                                        </td>
                                        <td class="d-print-none">
                                            <x-copy-target target="age_min" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('tools.ageInSec')</th>
                                        <td>
                                            <div class="text-break" id="age_sec">{{ $results['seconds'] }}</div>
                                        </td>
                                        <td class="d-print-none">
                                            <x-copy-target target="age_sec" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12 text-end d-print-none">
                            <x-print-button
                                onclick="ArtisanApp.printResult(document.querySelector('.result-printable'), {title: '{{ $tool->name }}'})"
                                :text="__('tools.printResult')" />
                        </div>
                    </div>
                </div>
            </x-page-wrapper>
        @endif
    </div>
</div>
