<?php

namespace App\Http\Controllers;

use App\Models\ProjobiUser;
use App\Services\PlatformService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Psy\CodeCleaner\ReturnTypePass;

use function PHPSTORM_META\type;

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
                (new PlatformService)->deactivateSubscription(json_decode($input)->resource->id);
            }
        } elseif($this->isGoodPaypalEvent(json_decode($input)->event_type))
        {
            $user = ProjobiUser::where('subscription_id', json_decode($input)->resource->id)->first();
            if($user && $user->is_subscriber == 'no')
            {
                (new PlatformService)->reactivateSubscription(json_decode($input)->resource->id);
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
}
