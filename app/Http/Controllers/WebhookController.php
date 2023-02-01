<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\ProjobiUser;
use App\Services\PlatformService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class WebhookController extends Controller
{
    public function webhook()
    {
        $input = file_get_contents("php://input");
        $logText = $this->logPaypalEvent($input);
        $infoMessage = ($logText ?? 'New Webhook Event Recived With "No message"');

        if($this->isBadPaypalEvent(json_decode($input)->event_type))
        {
            $user = ProjobiUser::where('subscription_id', json_decode($input)->resource->id)->first();
            
            if($user)
            {
                (new PlatformService)->suspendSubscription(json_decode($input)->resource->id);
            }
        } 
        elseif($this->isGoodPaypalEvent(json_decode($input)->event_type))
        {
            $user = ProjobiUser::where('subscription_id', json_decode($input)->resource->id)->first();
            if($user)
            {
                (new PlatformService)->reactivateSubscription(json_decode($input)->resource->id);
            }
        } 
        elseif($this->isPaymentCompletedPaypalEvent(json_decode($input)->event_type))
        {
            $user = ProjobiUser::where('subscription_id', json_decode($input)->resource->billing_agreement_id)->first();
            /* $plan = Plan::where('slug', $user->plan_slug)->first(); */
            /* return response()->json(['message' => 'Payment Completed', 'user' => $user, 'plan' => $plan, 'new_active_until' => Carbon::parse(json_decode($input)->create_time)->addDays($plan->duration_in_days)], 200); */
            if($user)
            {
                (new PlatformService)->paymentCompleted(json_decode($input)->resource->billing_agreement_id, json_decode($input)->create_time);
            }
        }

        Storage::append('webhook.log', $infoMessage);

    }

    public function logPaypalEvent($webhookData)
    {

        $event = json_decode($webhookData);

        $log =  '=================================================================================================================='. PHP_EOL .
                date(DATE_RFC2822) . " Se ha Registrado un Nuevo Evento ". '"' . $event->summary . '"' ." Evento: [ID: $event->id], (TYPE: $event->event_type)" . PHP_EOL .
                'Event will cancell subscription? ' . ($this->isBadPaypalEvent($event->event_type) ? 'Subscription: ' . $event->resource->id : 'Nahh..') . PHP_EOL .
                $webhookData . PHP_EOL .
                '=================================================================================================================='. PHP_EOL;

        return $log;
    }



    public function isBadPaypalEvent($type)
    {

        $unactiveEvents = ['BILLING.SUBSCRIPTION.CANCELLED', 'BILLING.SUBSCRIPTION.PAYMENT.FAILED', 'BILLING.SUBSCRIPTION.SUSPENDED'];
        if(in_array($type, $unactiveEvents))
        {
            return true;
        }
        return false;
    }

    public function isGoodPaypalEvent($type)
    {
        $activeEvents = ['BILLING.SUBSCRIPTION.CREATED', 'BILLING.SUBSCRIPTION.ACTIVATED', 'BILLING.SUBSCRIPTION.RE-ACTIVATED', 'BILLING.SUBSCRIPTION.PAYMENT.SUCCEEDED'];
        if(in_array($type, $activeEvents))
        {
            return true;
        }
        return false;
    }

    public function isPaymentCompletedPaypalEvent($type)
    {
        return $type == 'PAYMENT.SALE.COMPLETED';
    }

    public function getExpiredSubscriptors()
    {
        return UserService::getExpiredSubscriptions();
    }

    public function removeExpiredSubscriptors()
    {
        return UserService::removeExpiredSubscriptions();
    }
}
