<?php

namespace App\Components\Gateways;

use Exception;
use Carbon\Carbon;
use App\Models\Plan;
use Illuminate\Http\Request;
use App\Contracts\GatewayInterface;
use Mollie\Laravel\Facades\Mollie as MolliePay;

class Mollie implements GatewayInterface
{
    protected $request;
    protected $config;
    protected $mollie;

    public function __construct(Request $request, array $config)
    {
        $this->request = $request;
        $this->config = $config;
    }

    public function isActive(): bool
    {
        return (bool) setting('mollie_allow', 0);
    }

    public function isConfigured(): bool
    {
        return !empty($this->config['mollie_key']);
    }

    public function getName(): string
    {
        return "Mollie";
    }

    public function getIcon(): string
    {
        return '<svg width="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 578 512">
<path d="M269.8,260.2c-5.2-5.3-12.5-8.5-20.6-8.5c-16.2,0-29,13.4-29,29.3s12.8,29,29,29s29-13.4,29-29.3
	C278.2,272.8,275,265.5,269.8,260.2z M269.8,260.2c-5.2-5.3-12.5-8.5-20.6-8.5c-16.2,0-29,13.4-29,29.3s12.8,29,29,29
	s29-13.4,29-29.3C278.2,272.8,275,265.5,269.8,260.2z M269.8,260.2c-5.2-5.3-12.5-8.5-20.6-8.5c-16.2,0-29,13.4-29,29.3
	s12.8,29,29,29s29-13.4,29-29.3C278.2,272.8,275,265.5,269.8,260.2z M269.8,260.2c-5.2-5.3-12.5-8.5-20.6-8.5
	c-16.2,0-29,13.4-29,29.3s12.8,29,29,29s29-13.4,29-29.3C278.2,272.8,275,265.5,269.8,260.2z M269.8,260.2
	c-5.2-5.3-12.5-8.5-20.6-8.5c-16.2,0-29,13.4-29,29.3s12.8,29,29,29s29-13.4,29-29.3C278.2,272.8,275,265.5,269.8,260.2z
	 M269.8,260.2c-5.2-5.3-12.5-8.5-20.6-8.5c-16.2,0-29,13.4-29,29.3s12.8,29,29,29s29-13.4,29-29.3
	C278.2,272.8,275,265.5,269.8,260.2z M557,32H21.1C9.5,32,0,41.5,0,53.1v405.7C0,470.5,9.5,480,21.1,480h535.8
	c11.6,0,21.1-9.5,21.1-21V53.1C578,41.5,568.5,32,557,32z M183.2,328.3H163v-54c-0.3-12.3-10-22.3-22.2-22.3
	c-12.5,0-22.8,10.3-22.8,22.8v53.5H97.9v-54c0-12.3-10-22.3-22.2-22.3c-12.5,0-22.8,10.2-22.8,22.8v53.5H33v-54
	c0-23.3,19.1-42.4,42.4-42.4c12.5,0,24.5,5.4,32.4,15.1c15.4-17.9,42.1-20.2,60-4.8c9.7,7.5,15.4,19.5,15.4,32.6V328.3z
	 M248.9,330.2h-1.1c-26.2-0.8-47.2-22.4-47.5-48.9c-0.3-27.3,21.6-49.5,48.9-49.8c26.1,0.9,47.5,21.9,47.8,48.4
	C297.5,306.9,276.2,329.6,248.9,330.2z M336.2,328H316V184.3h20.2V328z M383.2,328H363V184.3h20.2V328z M430.1,328h-20.2v-93.9h20.2
	V328z M420.2,209.6c-7.6,0-13.9-6.3-13.9-13.9c0-7.7,6.3-13.9,13.9-13.9c7.6,0,13.9,6.2,13.9,13.9S427.8,209.6,420.2,209.6z
	 M545,287.6h-75.3c1.7,8.5,6.5,15.9,13.9,20.2c14.3,8.5,32.5,3.9,41-10l16.5,8c-8.5,14.8-24.4,23.9-41.5,24.2
	c-27.3,0.3-49.8-21.3-50.1-48.6c-0.3-27.3,21.3-49.8,48.6-50.1c25.9,0.2,46.9,21.3,46.9,47.2V287.6z M497.5,249.5
	c-13.1,0-24.7,9.1-27.6,22.2h55.2C522.2,258.6,510.9,249.5,497.5,249.5z M249.2,251.7c-16.2,0-29,13.4-29,29.3s12.8,29,29,29
	s29-13.4,29-29.3c0-8-3.2-15.2-8.4-20.5C264.5,255,257.3,251.7,249.2,251.7z M269.8,260.2c-5.2-5.3-12.5-8.5-20.6-8.5
	c-16.2,0-29,13.4-29,29.3s12.8,29,29,29s29-13.4,29-29.3C278.2,272.8,275,265.5,269.8,260.2z M269.8,260.2
	c-5.2-5.3-12.5-8.5-20.6-8.5c-16.2,0-29,13.4-29,29.3s12.8,29,29,29s29-13.4,29-29.3C278.2,272.8,275,265.5,269.8,260.2z
	 M269.8,260.2c-5.2-5.3-12.5-8.5-20.6-8.5c-16.2,0-29,13.4-29,29.3s12.8,29,29,29s29-13.4,29-29.3
	C278.2,272.8,275,265.5,269.8,260.2z M269.8,260.2c-5.2-5.3-12.5-8.5-20.6-8.5c-16.2,0-29,13.4-29,29.3s12.8,29,29,29
	s29-13.4,29-29.3C278.2,272.8,275,265.5,269.8,260.2z"/>
</svg>';
    }

    public function getViewName()
    {
        return "checkout.mollie";
    }

    public function initialize()
    {
        $this->mollie = MolliePay::api();
    }

    public function render()
    {
        return view('checkout.mollie')->render();
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
        } else {
            $amount = $plan->monthly_price;
        }


        try {
            $payment = $this->mollie->payments->create([
                "amount" => [
                    "currency" => setting('currency', "USD"),
                    "value" => $amount // You must send the correct number of decimals, thus we enforce the use of strings
                ],
                "description" => $plan->name,
                "redirectUrl" => route("payments.success", $transaction->id),

                "metadata" => [
                    "order_id" => $transaction->id,
                ],
            ]);

            if ($payment->isOpen()) {
                $transaction->transaction_id = $payment->id;
                $transaction->save();
            }
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }


        return redirect($payment->getCheckoutUrl(), 303);
    }

    public function verifyPayment($transaction, $request): bool
    {
        $mollie = MolliePay::api()->payments->get($transaction->transaction_id);

        return $mollie->isOpen() || $mollie->isPending() || $mollie->isPaid();
    }
}
