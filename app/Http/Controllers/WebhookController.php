<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebhookController extends Controller
{
    public function webhook()
    {
        $input = file_get_contents("php://input");
        $logText = $this->logEvent($input);
        $infoMessage = ($logText ?? 'New Webhook Event Recived With "No message"');

        Storage::append('webhook.log', $infoMessage);

    }

    public function logEvent($webhookData)
    {

        $event = json_decode($webhookData);

        $log =  '=================================================================================================================='. PHP_EOL .
                date(DATE_RFC2822) . " Se ha Registrado un Nuevo Evento ". '"' . $event->summary . '"' ." Evento: [ID: $event->id], (TYPE: $event->event_type)" . PHP_EOL .
                date(DATE_RFC2822) . ' - ' . $webhookData . PHP_EOL .
                '=================================================================================================================='. PHP_EOL;

        return $log;
    }
}
