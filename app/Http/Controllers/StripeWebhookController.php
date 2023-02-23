<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\ProjobiUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StripeWebhookController extends Controller
{

    public function webhookStripe()
    {
        $data = file_get_contents("php://input");
        if($this->isCancelable(json_decode($data)))
        {
            $this->cancelSubscription($this->getCancellableCustomerID(json_decode($data)));
        } else if($this->isRenewbable(json_decode($data)))
        {
            $this->renewSubscription($this->getRenewableCustomerID(json_decode($data)));
        }
        return response()->json(
            [
                'message' => 'Data Recived',
                'writeEventLog' => $this->writeEventLog($data)
            ],
             200        
            );
    }

    public function show()
    {
        if(Storage::exists('webhook-stripe.log'))
        {
            echo Storage::get('webhook-stripe.log');
        }
        else
        {
            echo 'No hay datos';
        }
    }

    public function download()
    {
        if(Storage::exists('webhook-stripe.log'))
        {
            return Storage::download('webhook-stripe.log', 'webhook-stripe-' . now()->toDateTimeString() . '.log');
        }
        else
        {
            echo 'No hay datos';
        }
    }

    public function pull()
    {
        if(Storage::exists('webhook-stripe.log'))
        {
            return response()->download(Storage::path('webhook-stripe.log'), 'webhook-stripe-' . now()->toDateTimeString() . '.log')->deleteFileAfterSend();
        }
        else
        {
            echo 'No hay datos';
        }
    }

    public function writeEventLog($data)
    {
        $log = $this->makeEventLog($data);
         return Storage::append('webhook-stripe.log', $log);
    }

    public function makeEventLog($data)
    {
        $data = json_decode($data);
        $log =  '=================================================================================================================='. PHP_EOL .
                date(DATE_RFC2822) . " Se ha Registrado un Nuevo Evento ". '"' . $data->type . '"' ." ID Evento [ID: $data->id], (TYPE: $data->type)" . PHP_EOL .
                json_encode($data) . PHP_EOL .
                '=================================================================================================================='. PHP_EOL;
        return $log;
    }

    protected function isBadStripeEvent($eventType)
    {
        $badEvents = [
            'invoice.payment_failed',
            'charge.failed',
            'customer.subscription.deleted',
            'payment_intent.payment_failed',
        ];
        if(in_array($eventType, $badEvents))
        {
            return true;
        }
        return false;
    }

    protected function isGoodStripeEvent($eventType)
    {
        $goodEvents = [
            'payment_intent.succeeded',
            'charge.succeeded',
        ];
        if(in_array($eventType, $goodEvents))
        {
            return $eventType == 'invoice.payment_succeeded';
        }
        return false;
    }

    protected function isCancelable($request)
    {
        $cancelables = [
            'payment_intent.payment_failed',
            'charge.failed',
            'customer.subscription.deleted',
            'customer.subscription.updated',
        ];

        if($request->type == 'customer.subscription.updated' && isset($request->object->pause_collection->behavior))
        {
            return true;
        }

        if(in_array($request->type, $cancelables))
        {
            return true;
        }
        return false;
    }

    protected function isRenewbable($request)
    {
        $renewables = [
            'invoice.payment_succeeded',
            'payment_intent.succeeded',
            'charge.succeeded',
            'customer.subscription.updated'
        ];

        if($request->type == 'customer.subscription.updated' && !isset($request->object->pause_collection->behavior))
        {
            return true;
        }

        if(in_array($request->type, $renewables))
        {
            return true;
        }
        return false;
    }

    protected function renewSubscription($subscriptionID)
    {
        $user = ProjobiUser::where('subscription_id', $subscriptionID)->first();
        $plan = Plan::where('slug', $user->plan_slug)->first();

        $user->subscription_status = 'active';
        $user->subscription_active_until = Carbon::parse($user->subscription_active_until)->addDays($plan->duration_in_days);

        return $user->save();
    }

    protected function cancelSubscription($subscriptionID)
    {
        return ProjobiUser::where('subscription_id', $subscriptionID)
                    ->update(['subscription_status' => 'suspended']);
    }

    protected function getCancellableCustomerID($webhookData)
    {
        return $webhookData->data->object->customer;
    }

    protected function getRenewableCustomerID($webhookData)
    {
        return $webhookData->data->object->customer;
    }

    protected function getPlanSlug($data)
    {
        // return config('services.stripe.plans');
        $data = json_decode($data);
        $plans = collect(config('services.stripe.plans'));
        return $plans->search( function($value, $key) use ($data) {
            return $value == $data->data->object->plan->id;
        });
    }
}
