<?php

namespace App\Services;

use App\Traits\ConsumeExternalServices;
use Illuminate\Http\Request;

class StripeService {
    use ConsumeExternalServices;

    protected $baseUri;
    protected $secret;
    protected $key;

    public function __construct()
    {
        $this->baseUri = config('services.stripe.base_uri');
        $this->secret = config('services.stripe.secret');
        $this->key = config('services.stripe.key');
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
                return redirect()
                    ->route('susbscription')
                    ->withSuccess(['success' => "Thanks $name, we received your $amount$ payment"]);
            }

            return redirect()
                ->route('susbscription')
                ->withErrors('We cannot confirm your payment. Try again, please');
        }

        return redirect()
            ->route('susbscription')
            ->withErrors('We cannot retrieve your payment intent. Try again, please');
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

    public function resolveFactor($currency)
    {
        $zeroDecimalCurrencies = ['JPY'];

        if (in_array(strtoupper($currency), $zeroDecimalCurrencies)) {
            return 1;
        }

        return 100;
    }
}