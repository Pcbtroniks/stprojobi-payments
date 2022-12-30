<?php

namespace App\Http\Controllers;

use App\Services\PaypalService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $rules = [
            'plan' => 'nullable|string',
            'value' => 'required|numeric',
            'currency' => 'required|exists:currencies,iso_code',
            'payment_platform' => 'required|exists:payment_platforms,id'
        ];

        $request->validate($rules);

        // $paymentPlatform = resolve($request->payment_platform);
        $paymentPlatform = resolve(PaypalService::class);

        // return $request->all();
        return $paymentPlatform->handlePayment($request);
    }

    public function approval()
    {
        $paymentPlatform = resolve(PaypalService::class);
        return $paymentPlatform->handleApproval();
    }
    
    public function cancelled()
    {
        return redirect()
            ->route('susbscription')
            ->withErrors('You cancelled the payment');
    }
}
