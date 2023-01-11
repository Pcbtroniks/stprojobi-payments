<?php

namespace App\Http\Controllers;

use App\Models\PaymentPlatform;
use App\Models\Plan;
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
        $paymentPlatforms = PaymentPlatform::where('subscriptions_enabled', true)->get();
        return view('admin.dashboard.subscriptions',
            compact('paymentPlatforms'));
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
        $rules = [
            'subscriptionId' => 'required',
            'plan' => 'required|exists:plans,slug'
        ];

        request()->validate($rules);

        $plan = Plan::where('slug', request()->plan)->firstOrFail();

        $user = request()->user();

    }

    public function cancelled()
    {
        return redirect()->route('subscribe.show')
            ->withErrors('Has cancelado la suscripción, puedes intentarlo de nuevo cuando quieras.');
    }
}
