<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StripeWebhookController extends Controller
{

    public function webhookStripe()
    {
        $data = file_get_contents("php://input");
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

    public function writeEventLog($data)
    {
        $log = $this->makeEventLog($data);
         return Storage::append('webhook-stripe.log', $log);
    }

    public function makeEventLog($data)
    {
        $log =  '=================================================================================================================='. PHP_EOL .
                date(DATE_RFC2822) . " Se ha Registrado un Nuevo Evento ". '"' . 'UNKNOWN' . '"' ." ID Evento [ID: UNKNOWN], (TYPE: UNKNOWN)" . PHP_EOL .
                $data . PHP_EOL .
                '=================================================================================================================='. PHP_EOL;
        return $log;
    }
}
