<x-application-no-widget-wrapper>
    <x-page-wrapper>
        <div class="hero-title center mb-5 mt-5">
            <h1>@lang('tools.checkoutTitle')</h1>
            <h5>@lang('tools.checkoutDescriptionFst')</h5>
            <p>@lang('tools.checkoutDescriptionSec')</p>
        </div>
        <x-form method="post" :route="route('payments.process')" id="process-form">
            <input type="hidden" name="plan_id" value="{{ $plan_id }}" required>
            <input type="hidden" name="type" value="{{ $type }}" required>
            <input type="hidden" name="price" value="{{ $price }}" required>
            <div class="row">
                <div class="col-md-4 order-md-2 mb-4">
                    <div class="row mb-4" id="gatewayview-div"></div>
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">@lang('tools.orderSummary')</span>
                    </h4>
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 class="my-0">{{ $plan->name }}</h6>
                                <small class="text-muted">{{ $plan->description }}</small>
                            </div>
                            <span class="text-muted">
                                <x-money amount="{{ $price ?? 0 }}" currency="{{ setting('currency', 'usd') }}" convert /> / {{ $type }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="fw-bold">@lang('common.total')</span>
                            <strong><x-money amount="{{ $price ?? 0 }}" currency="{{ setting('currency', 'usd') }}" convert /></strong>
                        </li>
                    </ul>
                    <hr class="mb-4">
                    <button class="btn btn-primary btn-lg btn-block w-100 mb-4"
                        type="submit" id="complete-purchase">@lang('tools.completePurchase')</button>
                </div>
                <div class="col-md-8 order-md-1">
                    <h4 class="mb-3">@lang('tools.paymentMethods')</h4>
                    <div class="row">
                        @foreach ($gateways as $key => $gateway)
                            <div class="col-md-6">
                                <div class="custom-radio-checkbox">
                                    <label class="radio-checkbox-wrapper">
                                        <input type="radio" class="radio-checkbox-input gateway-checkbox"
                                            name="gateway" value="{{ $key }}" checked />
                                        <span class="radio-checkbox-tile w-100">
                                            {!! $gateway->getIcon() !!}
                                            <span>{{ $gateway->getName() }}</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <hr class="mb-4">
                    <div class="row">
                        <h4 class="mb-3">@lang('tools.billingAddress')</h4>
                        <div class="col-md-6 mb-3">
                            <x-input-label>{{ __('tools.firstName') }}</x-input-label>
                            <input type="text" class="form-control valid-id" id="firstName" placeholder=""
                                value="{{ Auth::user()->name }}" required name="first_name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-input-label>{{ __('tools.lastName') }}</x-input-label>
                            <input type="text" class="form-control valid-id" id="lastName" placeholder="" value=""
                                required name="last_name">
                        </div>
                        <div class="col-md-12 mb-3">
                            <x-input-label>{{ __('tools.email') }}</x-input-label>
                            <input type="email" class="form-control valid-id" id="email" placeholder="you@example.com"
                                name="email" required value="{{ Auth::user()->email }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <x-input-label>{{ __('tools.addressLane1') }}</x-input-label>
                            <input type="text" class="form-control valid-id" id="address" placeholder="1234 Main St"
                                required name="address_lane_1">
                        </div>
                        <div class="col-md-12 mb-3">
                            <x-input-label>{{ __('tools.addressLane2') }} <span class="text-muted">(Optional)</span>
                            </x-input-label>
                            <input type="text" class="form-control" id="address2" placeholder="Apartment or suite"
                                name="address_lane_2">
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-input-label>{{ __('tools.countryCode') }}</x-input-label>
                            <input type="text" class="form-control" id="country_code" placeholder="US"
                                name="country_code">
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-input-label>{{ __('tools.postalCode') }}</x-input-label>
                            <input type="text" class="form-control valid-id" id="zip" placeholder="" required
                                name="postal_code">
                        </div>
                    </div>
                </div>
            </div>
        </x-form>
    </x-page-wrapper>
    @push('page_scripts')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script>
            const APP = function() {
                let gateway = document.querySelector("input[name=gateway]:checked").value
                const completePurchase = document.getElementById('complete-purchase');
                const getView = function() {
                        var inputs = document.querySelectorAll('.gateway-checkbox');
                        var gatewayValue;
                        for (var i = 0; i < inputs.length; i++) {
                            if (inputs[i].checked) {
                                gatewayValue = inputs[i].value;
                            }
                        }
                        ArtisanApp.showLoader()
                        axios.post(
                                '{{ route('payments.gateway-view') }}', {
                                    gateway: gatewayValue
                                })
                            .then((res) => {
                                ArtisanApp.hideLoader()
                                console.log(res, res.data, res.data.view);
                                document.getElementById('gatewayview-div').innerHTML = res.data.view;
                            })
                            .catch((err) => {
                                ArtisanApp.hideLoader()
                                resultError(element, cursor)
                            })
                    },
                    padStart = function(str) {
                        return ('0' + str).slice(-2)
                    },
                    demoSuccessHandler = function(transaction) {
                        document.getElementById('razor-pay-id').value = transaction.razorpay_payment_id;
                        document.getElementById('process-form').submit();
                    },
                    razorpayopen = function(e) {
                        e.preventDefault();
                        var firstname = document.getElementById('firstName').value;
                        var lastname = document.getElementById('lastName').value;
                        var email = document.getElementById('email').value;
                        var address = document.getElementById('address').value;
                        var zip = document.getElementById('zip').value;
                        if (firstname == "" || lastname == "" || email == "" || address == "" || zip == "") {
                            ArtisanApp.toastError("{{ __('common.billingInfoAlert') }}");
                            return;
                        }
                        console.log(Number({{ $price }} * 100).toFixed(2));
                        var options = {
                            key: "{{ config('services.gateways.razorpay.key') }}",
                            amount: Number({{ $price }} * 100).toFixed(2),
                            currency: "{{ setting('currency', 'usd') }}",
                            name: "{{ $plan->name }}",
                            description: "{{ $plan->description }}",
                            handler: demoSuccessHandler
                        }
                        window.r = new Razorpay(options);
                        r.open()
                    },
                    validationEvent = function() {
                        document.querySelectorAll('.valid-id').forEach(input => {
                            console.log(input);
                            if (input.value == "") {
                                input.classList.add("is-invalid");
                            } else {
                                input.classList.remove("is-invalid");
                            }
                            input.addEventListener('change', (e) => {
                                var input_value = e.target.value;
                                console.log(e);
                                if (input_value == "") {
                                    e.target.classList.add("is-invalid");
                                } else {
                                    e.target.classList.remove("is-invalid");
                                }
                            })
                        });
                    }
                    attachEvents = function() {
                        document.querySelectorAll('.gateway-checkbox').forEach(input => {
                        input.addEventListener('change', (e) => {
                            gateway = e.target.value
                            gateway == "razorpay" ?
                                completePurchase.addEventListener("click", razorpayopen, true) :
                                completePurchase.removeEventListener("click", razorpayopen, true);
                            getView()
                        })
                    });
                    };
                return {
                    init: function() {
                        attachEvents();
                        getView();
                        validationEvent();
                    }
                }
            }()
            document.addEventListener("DOMContentLoaded", function(event) {
                APP.init();
            });
        </script>
    @endpush
</x-application-no-widget-wrapper>
