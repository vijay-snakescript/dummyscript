<?php

namespace App\Components\Gateways;

use Exception;
use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Contracts\GatewayInterface;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPal implements GatewayInterface
{
    protected $paypal;
    protected $request;
    protected $config;

    public function __construct(Request $request, array $config)
    {
        $this->request = $request;
        $this->config = $config;
    }

    public function isActive(): bool
    {
        return (bool) setting('PAYPAL_ALLOW', 0);
    }

    public function isConfigured(): bool
    {
        $mode = $this->config['mode'];
        return ($this->config[$mode]['client_id'] != null && $this->config[$mode]['client_secret'] != null && $this->config[$mode]['app_id'] != null) ? 1 : 0;
    }

    public function getName(): string
    {
        return "PayPal";
    }

    public function getIcon(): string
    {
        return '<i class="an an-paypal"></i>';
    }

    public function getViewName()
    {
        return "checkout.paypal";
    }

    public function initialize()
    {
        $this->paypal = new PayPalClient($this->config);
        $this->paypal->setCurrency(setting('currency', "USD"));
        $this->paypal->getAccessToken();
    }

    public function render()
    {
        return view('checkout.paypal')->render();
    }

    public function processPayment($transaction)
    {
        try {
            $request = $this->request;
            $plan_id = $request->plan_id;
            $provider = $this->paypal;

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

            $response = $provider->addProduct($plan->name, $plan->description, 'SERVICE', 'SOFTWARE')
                ->$var_type($plan->name, $plan->description, $amount)
                ->setReturnAndCancelUrl(
                    route('payments.success', ['transaction_id' => $transaction->id]),
                    route('payments.cancel', ['transaction_id' => $transaction->id])
                )
                ->setupSubscription($request->first_name, $request->email, Carbon::now()->addMinute()->toIso8601String());
            $redirectLink = null;

            if ($response['status'] == "APPROVAL_PENDING" && isset($response['id'])) {
                $transaction->transaction_id = $response['id']; // update the transaction id for the database
                $transaction->update();
                $redirectLink  = $response['links'][0]['href'];
            }

            return $redirectLink ? redirect()->away($redirectLink) : redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function verifyPayment($transaction, $request): bool
    {
        $provider = $this->paypal;
        $response =  $provider->capturePaymentOrder($request->token);
        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            return true;
        }

        return false;
    }

    public function webhook(Request $request)
    {
        info($request->all());

        $transaction = Transaction::where('transaction_id', $request->id)->active()->latest()->first();

        return $transaction;
    }
}
