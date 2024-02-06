<div class="invoice-header">
            <h1>@lang('tools.invoice')</h1>
            <div class="actions">
                <a class="btn btn-outline-primary rounded-circle" type="button" id="button" data-toggle="tooltip"
                    aria-label="Download" href="{{ route('transaction.invoice.download',$transaction->id)}}">
                    <svg width="11" height="13" viewBox="0 0 11 13" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10.59 10.5V12.25H0.25V10.5H10.59ZM10.59 10.5V12.25H0.25V10.5H10.59ZM5.42 9.6L1.76 6.29C1.69 6.24 1.64 6.14 1.62 6.02V5.85V3.72L4.44 6.51V0H6.41V6.54L9.23 3.72V5.91C9.23 5.96 9.21 6.04 9.18 6.13L9.04 6.27L5.42 9.6Z">
                        </path>
                    </svg>
                </a>
                <button class="btn btn-outline-primary rounded-circle" type="button" id="button"
                    data-toggle="tooltip" aria-label="Print">
                    <svg width="13" height="13" viewBox="0 0 13 13" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12.25 3.6C12.33 3.68 12.37 3.78 12.37 3.89V10.26C12.37 10.39 12.33 10.49 12.25 10.57C12.17 10.65 12.06 10.69 11.94 10.69H9.56V12.25H2.94V10.69H0.560005C0.430005 10.69 0.330005 10.65 0.250005 10.57C0.170005 10.49 0.130005 10.38 0.130005 10.26V3.88C0.130005 3.77 0.170005 3.67 0.250005 3.59C0.330005 3.51 0.440005 3.47 0.560005 3.47H2.8V0H7.34V2.27H9.66V3.47H11.93C12.06 3.47 12.17 3.51 12.25 3.6ZM2.42 4.81H1.41V5.58H2.42V4.81ZM8.77001 8.64H3.68001V11.51H8.77001V8.64ZM8 9.19V9.68H4.45001V9.19H8ZM8 9.19V9.68H4.45001V9.19H8ZM8 9.19V9.68H4.45001V9.19H8ZM8 10.47V10.96H4.45001V10.47H8ZM4.45001 10.47H8V10.96H4.45001V10.47ZM4.45001 10.47H8V10.96H4.45001V10.47ZM9.53001 1.94H7.70001V0.11L9.53001 1.94Z">
                        </path>
                    </svg>
                </button>
            </div>
            <span>
                <x-application-logo />
            </span>
        </div>
        <article>
            <address>
                <h4 class="fw-bold">{{ $transaction->first_name }} {{ $transaction->last_name }}</h4>
                <p>{{ $transaction->address_lane_1 }} <br>{{ $transaction->address_lane_2 }}</p>
                <p>{{ $transaction->postal_code . ',' . $transaction->country_code }}</p>
            </address>
            <table class="meta table-invoice">
                <tr>
                    <th><span>@lang('tools.invoiceNo')</span></th>
                    <td><span>{{ $transaction->id }}</span></td>
                </tr>
                <tr>
                    <th><span>@lang('tools.date')</span></th>
                    <td><span>{{ $transaction->created_at->format('d M Y') }}</span></td>
                </tr>
                <tr>
                    <th><span>@lang('tools.amountDue')</span></th>
                    <td><span>
                            <x-money amount="{{ $transaction->amount ?? 0 }}" currency="{{ $transaction->currency }}"
                                convert />
                        </span></td>
                </tr>
            </table>
            <table class="inventory table-invoice">
                <thead>
                    <tr>
                        <th><span>@lang('tools.plan')</span></th>
                        <th><span>@lang('tools.description')</span></th>
                        <th><span>@lang('tools.rate')</span></th>
                        <th><span>@lang('tools.quantity')</span></th>
                        <th><span>@lang('tools.price')</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                            <td><span>{{ $transaction->plan_id == 0 ? ads_plan()->name : $transaction->plan->name }}</span></td>
                            <td><span>{{ $transaction->plan_id == 0 ? ads_plan()->description : $transaction->plan->description}}</span></td>
                        <td>
                            <x-money amount="{{ $transaction->amount ?? 0 }}" currency="{{ $transaction->currency }}"
                                convert />
                        </td>
                        <td><span>1</span></td>
                        <td>
                            <x-money amount="{{ $transaction->amount ?? 0 }}" currency="{{ $transaction->currency }}"
                                convert />
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="balance table-invoice">
                <tr>
                    <th><span>@lang('tools.total')</span></th>
                    <td><span>
                            <x-money amount="{{ $transaction->amount ?? 0 }}" currency="{{ $transaction->currency }}"
                                convert />
                        </span></td>
                </tr>
                <tr>
                    <th><span>@lang('tools.amountPaid')</span></th>
                    <td><span>
                            <x-money amount="{{ $transaction->amount ?? 0 }}" currency="{{ $transaction->currency }}"
                                convert />
                        </span></td>
                </tr>
            </table>
        </article>
        <div class="additional-notes">
            <h4><span>@lang('tools.additionalNotes')</span></h4>
            <div>
                <p>@lang('tools.additionalNoteHelp') </p>
            </div>
        </div>


        <div class="invoice-footer d-flex justify-content-between">
            <span>@lang('tools.web'): {{ url('/') }}</span>
            <span>@lang('tools.mail'): {{ setting('website_email') ?? 'example@mail.com' }}</span>
            <span>@lang('tools.tel'): {{ setting('contant_no') ?? '92 012 1234567' }} </span>
        </div>
