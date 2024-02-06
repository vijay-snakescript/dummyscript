<?php

namespace App\Components\Gateways;

use Exception;
use Stripe\Token;
use App\Models\Plan;
use Stripe\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Contracts\GatewayInterface;
use Stripe\Stripe as StripeGateway;

class Stripe implements GatewayInterface
{
    protected $stripe;
    protected $config;
    protected $stripeConfig;
    protected $request;
    protected $stripeToken;
    const BASE_URL = 'https://api.stripe.com';

    public function __construct(Request $request, array $config)
    {
        $this->request = $request;
        $this->config = $config;
    }

    public function isActive(): bool
    {
        return (bool) setting('STRIPE_ALLOW', 0);
    }

    public function isConfigured(): bool
    {
        return  $this->config['secretKey'] != null ? 1 : 0;
    }

    public function getName(): string
    {
        return "Stripe / Credit or Debit Card";
    }

    public function getIcon(): string
    {
        return '<i class="an an-card-visa"></i>';
    }

    public function initialize()
    {
        $this->stripe = new \Stripe\StripeClient($this->config['secretKey']);
    }

    public function render()
    {
        return view('checkout.stripe')->render();
    }

    public function processPayment($transaction)
    {
        try {

        $request = $this->request;
        $plan_id = $request->plan_id;

        if ($plan_id == 0) {
            $plan = ads_plan();
        } else {
            $plan = Plan::find($request->plan_id); //get from plan db
        }

        if ($request->type == "yearly") {
            $amount = $plan->yearly_price;
            $var_type = "year";
        } else {
            $amount = $plan->monthly_price;
            $var_type = "month";
        }

        $payment_url = self::BASE_URL . '/v1/payment_methods';

        $payment_data = [
            'type' => 'card',
            'card[number]' => $request->card_no,
            'card[exp_month]' => $request->exp_month,
            'card[exp_year]' => $request->exp_year,
            'card[cvc]' => $request->card_cvc,
            'billing_details[address][city]' => $request->card_no,
            'billing_details[address][state]' => $request->country_code,
            'billing_details[address][country]' => $request->country_code,
            'billing_details[address][line1]' => $request->address_lane_1,
            'billing_details[address][postal_code]' => $request->postal_code,
            'billing_details[email]' => $request->email,
            'billing_details[name]' => $request->first_name . ' ' . $request->last_name,
        ];

        $payment_payload = http_build_query($payment_data);

        $payment_headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Bearer ' . $this->config['secretKey']
        ];

        $payment_body = $this->curlPost($payment_url, $payment_payload, $payment_headers);

        $payment_response = json_decode($payment_body, true);


        if (isset($payment_response['id']) && $payment_response['id'] != null) {

            $request_url = self::BASE_URL . '/v1/payment_intents';

            $request_data = [
                'amount' => $amount * 100, // multiply amount with 100
                'currency' => setting('currency', "USD"),
                'payment_method_types[]' => 'card',
                'payment_method' => $payment_response['id'],
                'confirm' => 'true',
                'capture_method' => 'automatic',
                'return_url' => route("payments.success", $transaction->id),
                'payment_method_options[card][request_three_d_secure]' => 'automatic',
            ];

            $request_payload = http_build_query($request_data);

            $request_headers = [
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Bearer ' . $this->config['secretKey']
            ];

            // another curl request
            $response_body = $this->curlPost($request_url, $request_payload, $request_headers);

            $response_data = json_decode($response_body, true);
            if (isset($response_data->id)) {
                $transaction->transaction_id = $response_data->id; // update the transaction id for the database
                $transaction->update();
                return redirect()->route("payments.success", $transaction->id);
            }
            if (isset($response_data['next_action']['redirect_to_url']['url']) && $response_data['next_action']['redirect_to_url']['url'] != null) {

                return redirect()->away($response_data['next_action']['redirect_to_url']['url']);
            } elseif (isset($response_data['status']) && $response_data['status'] == 'succeeded') {

                return redirect()->route("payments.success", $transaction->id);
            } elseif (isset($response_data['error']['message']) && $response_data['error']['message'] != null) {

                return redirect()->route("payments.cancel", $transaction->id);
            } else {

                return redirect()->route("payments.cancel", $transaction->id);
            }
        } elseif (isset($payment_response['error']['message']) && $payment_response['error']['message'] != null) {

            return redirect()->route("payments.cancel", $transaction->id);
        }


        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function createStripeToken()
    {
        $request = $this->request;
        $number    = $request->card_no;
        $exp_month = $request->exp_month;
        $exp_year  = $request->exp_year;
        $cvc      = $request->card_cvc;
        $name      = $request->name_card;

        try {
            $response = Token::create(array(
                "card" => array(
                    "number"    => $number,
                    "exp_month" => $exp_month,
                    "exp_year"  => $exp_year,
                    "cvc"       => $cvc,
                    "name"      => $name
                )
            ));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        $res = $response->toArray();
        $token = $res['id'];

        return $token;
    }

    public function verifyPayment($transaction, $request): bool
    {
        $request_data = $request->all();

        // if only stripe response contains payment_intent
        if (isset($request_data['payment_intent']) && $request_data['payment_intent'] != null) {

            // here we will check status of the transaction with payment_intents from stripe server
            $get_url = self::BASE_URL . '/v1/payment_intents/' . $request_data['payment_intent'];

            $get_headers = [
                'Authorization: Bearer ' . $this->config['secretKey']
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $get_url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $get_headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $get_response = curl_exec($ch);

            curl_close($ch);

            $get_data = json_decode($get_response, 1);

            // get record of transaction from database
            // so we can verify with response and update the transaction status
            // $input = \DB::table('transactions')
            //     ->where('transaction_id', $transaction_id)
            //     ->first();

            // here you can check amount, currency etc with $get_data
            // which you can check with your database record
            // for example amount value check
            // if ($input['amount'] * 100 == $get_data['amount']) {
            //     // nothing to do
            // } else {
            //     // something wrong has done with amount
            // }

            // succeeded means transaction success
            if (isset($get_data['status']) && $get_data['status'] == 'succeeded') {

                return true;

                // update here transaction for record something like this
                // $input = \DB::table('transactions')
                //     ->where('transaction_id', $transaction_id)
                //     ->update(['status' => 'success']);

            } elseif (isset($get_data['error']['message']) && $get_data['error']['message'] != null) {

                return false;
            } else {

                return false;
            }
        } else {

            return false;
        }
        return true;
    }

    public function webhook(Request $request)
    {
        info($request->all());

        $transaction = Transaction::where('transaction_id', $request->id)->active()->latest()->first();

        return $transaction;
    }

    private function curlPost($url, $data, $headers)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }
}
