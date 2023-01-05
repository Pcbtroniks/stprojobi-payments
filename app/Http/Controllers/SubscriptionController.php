<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resolvers\PaymentPlatformResolver;

class SubscriptionController extends Controller
{
    protected $paymentPlatformResolver;

    public function __construct(PaymentPlatformResolver $paymentPlatformResolver)
    {
        $this->middleware('auth');
        $this->paymentPlatformResolver = $paymentPlatformResolver;
    }

    public function show()
    {
        return view('subscription.show');
    }

    public function store(Request $request)
    {

        // Validations
        $rules = [
            'plan' => 'required|exists:plans,slug',
            'payment_platform' => 'required|exists:payment_platforms,id'
        ];
        $request->validate($rules);

        // Resolve the payment platform
        $paymentPlatform = $this->paymentPlatformResolver->resolveService($request->payment_platform);

        // Process the payment
        session()->put('subscription_platform_id', $request->payment_platform);
        return $paymentPlatform->handleSubscription($request);

    }

    public function approval()
    {
        return view('subscription.approval');
    }

    public function cancelled()
    {
        return view('subscription.cancelled');
    }
}
