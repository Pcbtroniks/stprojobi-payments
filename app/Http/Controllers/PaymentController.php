<?php

namespace App\Http\Controllers;

use App\Resolvers\PaymentPlatformResolver;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentPlatformResolver;

    public function __construct(PaymentPlatformResolver $paymentPlatformResolver)
    {
        $this->middleware('auth');
        $this->paymentPlatformResolver = $paymentPlatformResolver;
    }

    public function pay(Request $request)
    {
        $rules = [
            'plan' => 'nullable|string',
            'value' => 'required|numeric',
            'currency' => 'required|exists:currencies,iso_code',
            'payment_platform' => 'required|exists:payment_platforms,id'
        ];
//dd($request->all());
        $request->validate($rules);

        $paymentPlatform = $this->paymentPlatformResolver->resolveService($request->payment_platform);

        session()->put('paymentPlatformId', $request->payment_platform);

        return $paymentPlatform->handlePayment($request);
    }

    public function approval()
    {
        if (session()->has('paymentPlatformId')) {
            $paymentPlatform = $this->paymentPlatformResolver->resolveService(session()->get('paymentPlatformId'));
            return $paymentPlatform->handleApproval();
        }
        return redirect()
            ->route('susbscription')
            ->withErrors('We cannot retrieve your payment platform. Try again, please');
    }
    
    public function cancelled()
    {
        return redirect()
            ->route('susbscription')
            ->withErrors('You cancelled the payment');
    }
}
