<?php

namespace App\Http\Controllers;

use App\Models\PaymentPlatform;
use App\Models\Plan;
use App\Models\ProjobiUser;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Resolvers\PaymentPlatformResolver;
use App\Services\PlatformService;

class SubscriptionController extends Controller
{
    protected $paymentPlatformResolver;

    public function __construct(PaymentPlatformResolver $paymentPlatformResolver)
    {
        $this->middleware(['projobi.user.verify']);
        $this->paymentPlatformResolver = $paymentPlatformResolver;
    }

    public function show()
    {
        $paymentPlatforms = PaymentPlatform::where('subscriptions_enabled', true)->get();
        // check if the user had a subscription before
        if(session()->has('projobi_user') && session()->get('projobi_user.subscription_id') !== null){
            return view('admin.dashboard.subscriptions-no-trial',
            compact('paymentPlatforms'));
        }
        //
        return view('admin.dashboard.subscriptions',
            compact('paymentPlatforms'));
    }

    public function store(Request $request)
    {
        //dd(request()->all());
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
            'subscription_id' => 'required',
            'plan' => 'required|exists:plans,slug'
        ];


        request()->validate($rules);

        if (session()->has('subscription_platform_id')) 
        {
            $paymentPlatform = $this->paymentPlatformResolver->resolveService(session()->get('subscription_platform_id'));

            if($paymentPlatform->validateSubscription(request()))
            {


                $plan = Plan::where('slug', request()->plan)->firstOrFail();
                
                if(session()->has('projobi_user') && session()->has('projobi_user.id'))
                {
                    $projobiUser = ProjobiUser::find(session()->get('projobi_user.id'));
                    $subscriptionIdentifier = request()->has('customer_id') ? request()->customer_id : request()->subscription_id;
                    (new PlatformService)->activateSubscription($projobiUser, 'yes', $subscriptionIdentifier, request()->plan, now()->addDays($plan->duration_in_days));
                    session()->invalidate();
                    if(config('app.env') === 'production')
                    {
                        return redirect('https://projobi.com/dashboard/suscripcion');
                    } else {
                        return redirect('http://projobi.test/dashboard/suscripcion');
                    }
                }
                else
                {
                    $user = session()->has('projobi_user') 
                    ? session()->get('projobi_user')
                    : auth()->user();

                    $subscription = Subscription::create([
                        'active_until' => now()->addDays($plan->duration_in_days),
                        'user_id' => $user->id,
                        'plan_id' => $plan->id, 
                    ]);
                }

                return redirect()->route('subscribe.show')
                    ->with(['success' => 'Has iniciado una suscripción nueva por ' . $plan->duration_in_days . ' días. Comienza a disfrutar de los beneficios de tu suscripción.']);
            }

        }

        return redirect()->route('subscribe.show')
            ->withErrors('Lo sentimos, no hemos podido iniciar tu suscripción, por favor intenta de nuevo o ponte en contacto con nosotros para más información');

    }

    public function cancelled()
    {
        return redirect()->route('subscribe.show')
            ->withErrors('Has cancelado la suscripción, puedes intentarlo de nuevo cuando quieras.');
    }

    public function planX()
    {
        return view('admin.planx.plan-x', [
            'paymentPlatforms' => PaymentPlatform::where('subscriptions_enabled', true)->get(),
        ]);
    }

}
