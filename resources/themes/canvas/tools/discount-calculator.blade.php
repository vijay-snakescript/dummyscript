<x-application-tools-wrapper>
    <x-tool-wrapper :tool="$tool">
        <x-ad-slot :advertisement="get_advert_model('above-form')" />
        <x-form id="frmDiscount" method="get" :route="route('tool.handle', $tool->slug)" class="no-app-loader">
            <div class="box-shadow mb-3 py-5">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <x-input-label>@lang('tools.amount')</x-input-label>
                            <x-text-input class="form-control" name="amount" type="number" step="0.01" id="amount" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <x-input-label>@lang('tools.discountPercentage')</x-input-label>
                            <x-text-input class="form-control" name="discount" type="number" step="0.01" id="discount" required />
                        </div>
                    </div>
                </div>
            </div>
            <x-ad-slot :advertisement="get_advert_model('below-form')" />
            <div class="row">
                <div class="col-md-12 text-end">
                    <x-button type="submit" id="calculateDiscount" class="btn btn-outline-primary rounded-pill">
                        @lang('tools.calculateDiscount')
                    </x-button>
                </div>
            </div>
        </x-form>
    </x-tool-wrapper>
    <div class="tool-result-wrapper d-none">
        <x-page-wrapper :title="__('common.result')">
            <div class="result mt-4">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-style mb-0">
                            <tbody>
                                <tr>
                                    <th>@lang('tools.discountedPrice')</th>
                                    <td>
                                        <div class="text-break" id="discounted-price"></div>
                                    </td>
                                    <td>
                                        <x-copy-target target="discounted-price" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>@lang('tools.savings')</th>
                                    <td>
                                        <div class="text-break" id="saving-price"></div>
                                    </td>
                                    <td>
                                        <x-copy-target target="saving-price" />
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
                const resultsElem = document.querySelector('.tool-result-wrapper');
                const calculation = function() {
                        var amount = document.getElementById('amount').value;
                        var discount = document.getElementById('discount').value;

                        if (amount < 0 || discount < 0 || amount == "" || discount == "") {
                            ArtisanApp.toastError('{{ __('tools.invalidInput') }}')
                            return;
                        }

                        amount = parseFloat(amount);
                        discount = parseFloat(discount);
                        var discounted_price = 0;
                        var saving_price = 0;

                        saving_price = (discount * amount) / 100;
                        discounted_price = amount - saving_price;

                        document.getElementById('discounted-price').innerHTML = discounted_price.round(2);
                        document.getElementById('saving-price').innerHTML = saving_price.round(2);

                        resultsElem.classList.remove('d-none')
                        resultsElem.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                        ArtisanApp.hideLoader();
                    },
                    attachEvents = function() {
                        document.getElementById('calculateDiscount').addEventListener('click', () => {
                            calculation()
                        })
                        document.getElementById('frmDiscount').addEventListener('submit', e => {
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
