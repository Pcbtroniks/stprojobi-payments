<?php

namespace App\Services;

use App\Traits\ConsumeExternalServices;

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

    public function handlePayment($request)
    {
        //Code
    }

    public function handleAproval($request)
    {
        //Code 
    }

    public function resolveFactor($currency)
    {
        //Code 
    }
}