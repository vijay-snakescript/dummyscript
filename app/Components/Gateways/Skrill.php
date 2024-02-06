<?php

namespace App\Components\Gateways;

use Exception;
use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\PointHistory;
use Illuminate\Http\Request;
use App\Contracts\GatewayInterface;
use Obydul\LaraSkrill\SkrillClient;
use Obydul\LaraSkrill\SkrillRequest;
use Illuminate\Support\Facades\Redirect;

class Skrill implements GatewayInterface
{
    protected $request;
    protected $config;
    protected $skrillRequest;
    protected $skrillClient;

    public function __construct(Request $request, array $config)
    {
        $this->request = $request;
        $this->config = $config;

        // skrill config
        $this->skrillRequest = new SkrillRequest();
        $this->skrillClient = new SkrillClient($this->skrillRequest);
        $this->skrillRequest->pay_to_email = setting('skrill_merchant_email'); // your merchant email
        $this->skrillRequest->status_url = route('payments.webhook-listener' , ['gateway'=>'skrill']);  // you can use https://webhook.site webhook url as test IPN
    }

    public function isActive(): bool
    {
        return (bool) setting('skrill_allow', 0);
    }

    public function isConfigured(): bool
    {
        return !empty(setting('skrill_merchant_email'));
    }

    public function getName(): string
    {
        return "Skrill";
    }

    public function getIcon(): string
    {
        return '<svg width="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 578 512">
<path d="M557,32H21.1C9.5,32,0,41.5,0,53.1v405.7C0,470.5,9.5,480,21.1,480h535.8c11.6,0,21.1-9.5,21.1-21V53.1
	C578,41.5,568.5,32,557,32z M400.8,169.2c11.6,0,21,9.4,21,20.9s-9.4,20.9-21,20.9c-11.6,0-21-9.4-21-20.9S389.2,169.2,400.8,169.2z
	 M92.8,342.8c-21.7,0-40.3-2.1-56.8-6.4v-38c11,4.5,29.1,9.2,47.8,9.2c17.1,0,27.2-5.6,27.2-15.1c0-11.5-12.4-12.2-17.1-12.5
	c-55-3.7-60.9-36-60.9-49.7c0-23.9,17-51.7,65.1-51.7c27.8,0,42.9,4.3,55.8,8.8l0.6,0.2v36.8l-0.5-0.1c-5.2-2.2-11.3-4.3-11.3-4.3
	c-10.9-3.2-26.3-6.7-38.1-6.7c-6.7,0-24.7,0-24.7,13.5c0,11.1,12.5,12,17.9,12.3c39.5,2.6,63,21.8,63,51.3
	C160.8,316.5,139.8,342.8,92.8,342.8z M287.2,341.2h-52.7c0,0-6.3-28.6-19-47.6v47.6h-44V187.2l44-8.6v84.9
	c16.5-19.9,23.4-39,24.3-41.4h50.3c-1.5,4.6-10.4,32-30.9,57.8C259.2,280,282,319.7,287.2,341.2z M366.7,256
	c-24.9,0.8-27.7,9.8-27.7,30.7v54.5h-43v-66.9c0-34.9,20-52.4,59.4-53.7c0,0,7.1-0.2,11.3,0.6V256z M422.1,341.2h-42.6V222.3h42.6
	V341.2z M482.8,341.2h-42.6V186.2l42.6-7.6V341.2z M545,341.2h-42.6V186.2l42.6-7.6V341.2z"/>
</svg>

';
    }

    public function getViewName()
    {
        return "checkout.skrill";
    }

    public function initialize()
    {
    }

    public function render()
    {
        return view('checkout.skrill')->render();
    }

    public function processPayment($transaction)
    {
        $request = $this->request;
        $plan_id = $request->plan_id;
        if ($plan_id == 0) {
            $plan = ads_plan();
        } else {
            $plan = Plan::find($request->plan_id); //get from plan db
        }

        if ($request->type == "yearly") {
            $amount = $plan->yearly_price;
            $var_type = "addAnnualPlan";
        } else {
            $amount = $plan->monthly_price;
            $var_type = "addMonthlyPlan";
        }


        // create object instance of SkrillRequest
        $this->skrillRequest->prepare_only = 1;
        $this->skrillRequest->amount = $amount;
        $this->skrillRequest->currency = setting('currency', "USD");
        $this->skrillRequest->language = 'EN';

        $this->skrillRequest->detail1_description = $plan->name;
        $this->skrillRequest->detail1_text = $plan->description;
        $this->skrillRequest->return_url = route("payments.success", $transaction->id);
        $this->skrillRequest->cancel_url = route("payments.cancel", $transaction->id);
        $this->skrillRequest->frn_trn_id = $transaction->id;

        $sid = $this->skrillClient->generateSID(); //return SESSION ID
        // handle error
        $jsonSID = json_decode($sid);
        if ($jsonSID != null && $jsonSID->code == "BAD_REQUEST") {
            dd($jsonSID->message);
            return redirect()->back()->withErrors($jsonSID->message);
        }
        // do the payment
        $transaction->transaction_id = $sid;
        $transaction->update();
        $redirectUrl = $this->skrillClient->paymentRedirectUrl($sid); //return redirect url
        return Redirect::to($redirectUrl); // redirect user to Skrill payment page

    }

    public function verifyPayment($transaction, $request): bool
    {
        $transaction_id = $request->transaction_id;
        $status = $request->status;

        if ($status == '-2') {
            return false;
        } else if ($status == '2') {
            return false;
        } else if ($status == '0') {
            return false;
        } else if ($status == '-1') {
            return false;
        }

        $transaction = Transaction::where('transaction_id', $transaction_id)->first();
        $user = $transaction->user;


        $transaction->expiry_date = $transaction->plan_type == "yearly" ? now()->addYear() : now()->addMonth();
        $transaction->status = 1;
        $transaction->response = __('document.successfull');
        $transaction->update();

        return true;
    }


    public function webhook($transaction, $request)
    {
        $transaction_id = $request->transaction_id;
        $status = $request->status;

        if ($status == '-2') {
            return false;
        } else if ($status == '2') {
            return false;
        } else if ($status == '0') {
            return false;
        } else if ($status == '-1') {
            return false;
        }

        $transaction = Transaction::where('transaction_id', $transaction_id)->first();
        $user = $transaction->user;

        $transaction->expiry_date = $transaction->plan_type == "yearly" ? now()->addYear() : now()->addMonth();
        $transaction->status = 1;
        $transaction->response = __('document.successfull');
        $transaction->update();


        return null;
    }
}
