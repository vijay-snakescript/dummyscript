<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form id="frmTax" class="no-app-loader" method="get" :route="route('tool.handle', $tool->slug)">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel-left-generator box-shadow mb-3 py-5">
                        <div class="panel-left-radio">
                            <div class="panel-left">
                                <div class="controller">
                                    <div class="custom-radio-checkbox">
                                        <label class="radio-checkbox-wrapper">
                                            <input type="radio" id="inclusive" class="radio-checkbox-input"
                                                name="type" value="1"
                                                @if (isset($type) && $type == '1') checked @endif />
                                            <span class="radio-checkbox-tile">
                                                <span>@lang('tools.inclusive')</span>
                                            </span>
                                        </label>
                                    </div>
                                    <div class="custom-radio-checkbox">
                                        <label class="radio-checkbox-wrapper mb-0">
                                            <input type="radio" id="exclusive" class="radio-checkbox-input"
                                                name="type" value="2"
                                                @if (isset($type) && $type == '2') checked @endif />
                                            <span class="radio-checkbox-tile">
                                                <span>@lang('tools.exclusive')</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="textarea ">
                                    <div class="form-group mb-4">
                                        <x-text-input class="form-control" name="amount" type="number" id="amount"
                                            required step="0.01" :placeholder="__('tools.amount')" />
                                    </div>
                                    <div class="form-group">
                                        <x-text-input class="form-control" name="tax" type="number" id="tax"
                                            required step="0.01" :placeholder="__('tools.taxPercentage')" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-ad-slot class="mt-3" :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="submit" class="btn btn-outline-primary rounded-pill" id="calculateTax">
                        @lang('tools.calculateTax')
                    </x-button>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    <div class="d-none sales-tax-results">
        <x-ad-slot :advertisement="get_advert_model('above-result')" />
        <x-page-wrapper :title="__('common.result')">
            <div class="result mt-4">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-style mb-0">
                            <tbody>
                                <tr>
                                    <th>@lang('tools.netAmount')</th>
                                    <td>
                                        <div class="text-break" id="net-amount"></div>
                                    </td>
                                    <td>
                                        <x-copy-target target="net-amount" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>@lang('tools.taxRate')</th>
                                    <td>
                                        <div class="text-break" id="tax-rate"></div>
                                    </td>
                                    <td>
                                        <x-copy-target target="tax-rate" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>@lang('tools.grossAmount')</th>
                                    <td>
                                        <div class="text-break" id="gross-amount"></div>
                                    </td>
                                    <td>
                                        <x-copy-target target="gross-amount" />
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
    <x-tool-content :tool="$tool" />
    @push('page_scripts')
        <script>
            const APP = function() {
                const resultsElem = document.querySelector('.sales-tax-results');
                const calculation = function() {
                        var amount = document.getElementById('amount').value;
                        var tax = document.getElementById('tax').value;

                        if (amount < 0 || amount == null || tax < 0 || tax == null || amount == "" || tax == "") {
                            ArtisanApp.toastError('{{ __('tools.invalidInput') }}')
                            return;
                        }

                        amount = parseFloat(amount);
                        tax = parseFloat(tax);
                        var tax_amount = 0;
                        var net_amount = 0;
                        var gross_amount = 0;

                        if (document.getElementById('inclusive').checked == true) {
                            gross_amount = amount;
                            net_amount = amount * 100 / (100 + tax);
                            tax_amount = parseFloat(gross_amount) - parseFloat(net_amount);
                        }
                        if (document.getElementById('exclusive').checked == true) {
                            net_amount = amount;
                            tax_amount = (tax / 100) * amount;
                            gross_amount = parseFloat(net_amount) + parseFloat(tax_amount);
                        }

                        document.getElementById('gross-amount').innerHTML = gross_amount.round(2);
                        document.getElementById('net-amount').innerHTML = net_amount.round(2);
                        document.getElementById('tax-rate').innerHTML = tax + ' % tax i.e.' + tax_amount.round(2);

                        resultsElem.classList.remove('d-none')
                        resultsElem.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    },
                    attachEvents = function() {
                        document.getElementById('calculateTax').addEventListener('click', () => {
                            calculation()
                        })
                        document.getElementById('frmTax').addEventListener('submit', e => {
                            e.preventDefault();
                            calculation()
                        })
                    };

                return {
                    init: function() {
                        attachEvents();
                    }
                }
            }()
            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
    @endpush
</x-application-tools-wrapper>
