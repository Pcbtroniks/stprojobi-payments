<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StripeWebhookController extends Controller
{

    public function webhookStripe()
    {
        $data = file_get_contents("php://input");
        if($this->isBadStripeEvent(json_decode($data)->type))
        {
            $this->writeEventLog($data);
            return response()->json(
                [
                    'message' => 'Data Recived', 
                    'writeEventLog' => $this->writeEventLog($data)
                ],
                 200        
                );
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

    protected function handleEvent()
    {

    }

    protected function reactivateSubscription()
    {

    }
}
