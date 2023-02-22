<?php

namespace App\Services;

use App\Traits\ConsumeExternalServices;
use Illuminate\Http\Request;

class StripeService {
    use ConsumeExternalServices;

    protected $baseUri;
    protected $secret;
    protected $key;
    protected $plans;

    public function __construct()
    {
        $this->baseUri = config('services.stripe.base_uri');
        $this->secret = config('services.stripe.secret');
        $this->key = config('services.stripe.key');
        $this->plans = config('services.stripe.plans');
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
        return "Bearer {$this->secret}";
    }

    public function handlePayment(Request $request)
    {
        $request->validate([
            'value' => 'required|numeric|min:5',
            'currency' => 'required|size:3',
            'payment_method' => 'required'
        ]);

        $intent = $this->createIntent($request->value, $request->currency, $request->payment_method);

        session()->put('paymentIntentId', $intent->id);

        return redirect()->route('approval');
    }

    public function handleApproval()
    {
        if (session()->has('paymentIntentId')) {
            $paymentIntentId = session()->get('paymentIntentId');

            $confirmation = $this->confirmPayment($paymentIntentId);

            if ($confirmation->status === 'succeeded') {
                $name = auth()->user()->name;
                $currency = strtoupper($confirmation->currency);
                $amount = $confirmation->amount / $this->resolveFactor($currency);

                return redirect()->route('subscribe.show')
                    ->with(['success' => 'Has iniciado una suscripción nueva por. Comienza a disfrutar de los beneficios de tu suscripción.']);
            }

            return redirect()->route('subscribe.show')
            ->withErrors('Lo sentimos, no hemos podido iniciar tu suscripción, por favor intenta de nuevo o ponte en contacto con nosotros para más información');
        }

        return redirect()->route('subscribe.show')
            ->withErrors('Lo sentimos, no hemos podido iniciar tu suscripción, por favor intenta de nuevo o ponte en contacto con nosotros para más información.');
    }


    public function handleSubscription(Request $request)
    {

        $customer = null;
        if(session()->has('projobi_user'))
        {
            $customer = $this->createCustomer(
                session()->get('projobi_user.name'),
                session()->get('projobi_user.email'),
                $request->payment_method
            );
            
        }
        else
        {
            $customer = $this->createCustomer(
                $request->user()->name,
                $request->user()->email,
                $request->payment_method
            );
        }

        $subscription = $this->createSubscription(
            $customer->id,
            $request->payment_method,
            $this->plans[$request->plan],
        );

        if($subscription->status == 'active')
        {
            session()->put('subscriptionId', $subscription->id);
            return redirect()->route('subscribe.approval', [
                'plan' => $request->plan,
                'subscription_id' => $subscription->id,
            ]);
        }
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

    public function createIntent($value, $currency, $paymentMethod = null, $confirm = 'manual')
    {
        return $this->makeRequest(
            'POST',
            '/v1/payment_intents',
            [],
            [
                'amount' => round($this->resolveFactor($currency) * $value),
                'currency' => strtolower($currency),
                'payment_method' => $paymentMethod,
                'confirmation_method' => $confirm
            ]
        ); 
    }

    public function confirmPayment($paymentIntentId)
    {
        return $this->makeRequest(
            'POST',
            "/v1/payment_intents/{$paymentIntentId}/confirm"
        );
    }

    public function createCustomer($name, $email, $paymentMethod)
    {
        return $this->makeRequest(
            'POST',
            '/v1/customers',
            [],
            [
                'name' => $name,
                'email' => $email,
                'payment_method' => $paymentMethod
            ]
        );
    }

    public function createSubscription($customerId, $paymentMethod, $priceId)
    {
        return $this->makeRequest(
            'POST',
            '/v1/subscriptions',
            [],
            [
                'customer' => $customerId,
                'items' => [
                    [
                        'price' => $priceId
                    ]
                ],
                'default_payment_method' => $paymentMethod
            ]
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