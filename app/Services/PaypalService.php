<?php

namespace App\Services;

use App\Traits\ConsumeExternalServices;
use Illuminate\Http\Request;

class PaypalService {
    use ConsumeExternalServices;

    protected $baseUri;
    protected $secret;
    protected $client_id;
    protected $plans;

    public function __construct()
    {
        $this->client_id = config('services.paypal.client_id');
        $this->baseUri = config('services.paypal.base_uri');
        $this->secret = config('services.paypal.secret');
        $this->plans = config('services.paypal.plans');
    }

    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        $headers['Authorization'] = $this->resolveAccessToken();
    }
    
    public function decodeResponse($response)
    {
        return json_decode($response);
    }

    public function resolveAccessToken()
    {
        $credentials = base64_encode("{$this->client_id}:{$this->secret}");

        return "Basic {$credentials}";
    }

    public function handleSubscription(Request $request)
    {
        if(session()->has('projobi_user'))
        {
            $subscription = $this->createSubscription(
                $request->plan,
                session()->get('projobi_user.name'),
                session()->get('projobi_user.email')
            );
        }
        else
        {
            $subscription = $this->createSubscription(
                $request->plan,
                $request->user()->name,
                $request->user()->email
            );
        }

        $subscriptionLinks = collect($subscription->links);

        $approve = $subscriptionLinks->where('rel', 'approve')->first();

        session()->put('subscriptionId', $subscription->id);

        return redirect($approve->href);
    }

    public function validateSubscription(Request $request)
    {
        if(session()->has('subscriptionId'))
        {
            $subscriptionID = session()->get('subscriptionId');

            session()->forget('subscriptionId');

            return $request->subscription_id == $subscriptionID;
        }

        return false;
    }

    public function createOrder($value, $currency)
    {
        return $this->makeRequest(
            'POST',
            '/v2/checkout/orders',
            [],
            [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    0 => [
                        'amount' => [
                            'currency_code' => strtoupper($currency),
                            'value' => round($value * $factor = $this->resolveFactor($currency)) / $factor,
                        ]
                    ]
                ],
                'application_context' => [
                    'brand_name' => config('app.name'),
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action' => 'PAY_NOW',
                    'cancel_url' => route('cancelled'),
                    'return_url' => route('approval'),
                ]
            ],
            [],
            $isJsonRequest = true,
        );
    }

    public function capturePayment($approvalID)
    {
        return $this->makeRequest(
            'POST',
            "/v2/checkout/orders/{$approvalID}/capture",
            [],
            [],
            [
                'Content-Type' => 'application/json'
            ],
        );
    }

    public function handlePayment($request)
    {
        $order = $this->createOrder($request->value, $request->currency);

        $orderLinks = collect($order->links);

        $approve = $orderLinks->where('rel', 'approve')->first();

        session()->put('approvalId', $order->id);

        return redirect($approve->href);
    }

    public function handleApproval()
    {
        if (session()->has('approvalId')) {
            $approvalId = session()->get('approvalId');

            $payment = $this->capturePayment($approvalId);

            $name = $payment->payer->name->given_name;
            $paymentValue = $payment->purchase_units[0]->payments->captures[0]->amount;
            $amount = $paymentValue->value;
            $paymentCurrency = $paymentValue->currency_code;

            return redirect()
                ->route('susbscription')
                ->withSuccess(['success' => "Thanks, {$name}. We received your {$amount}{$paymentCurrency} payment."]);
        }

        return redirect()
            ->route('susbscription')
            ->withErrors('We cannot capture your payment. Try again, please');
    }

    public function createSubscription($planSlug, $name, $email)
    {
        return $this->makeRequest(
            'POST',
            '/v1/billing/subscriptions',
            [],
            [
                'plan_id' => $this->plans[$planSlug],
                'subscriber' => [
                    'name' => [
                        'given_name' => $name,
                    ],
                    'email_address' => $email
                ],
                'application_context' => [
                    'brand_name' => config('app.name'),
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action' => 'SUBSCRIBE_NOW',
                    'cancel_url' => route('subscribe.cancelled'),
                    'return_url' => route('subscribe.approval', ['plan' => $planSlug]),
                ]
            ],
            [],
            $isJsonRequest = true,
        );
    }

    public function resolveFactor($currency)
    {
        $zeroDecimalCurrencies = ['JPY'];

        if (in_array(strtoupper($currency), $zeroDecimalCurrencies)) {
            return 1;
        }

        return 100;
    }
}