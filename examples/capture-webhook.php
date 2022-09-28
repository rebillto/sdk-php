<?php


/*
Procesamiento de un Webhook
Los datos enviados por webhook se envían en forma POST con un body codificado en JSON

Los datos podrían ser leídos e interpretados de la siguiente forma:
*/


$json = file_get_contents('php://input');

file_put_contents('webhook.log', '---------- '.date('c')." -------------- \n\nEVENT: {$_GET['event']} \n\nPOST: $json \n\n", FILE_APPEND | LOCK_EX);

$data = json_decode($json, true);
switch($_GET['event']) {
    case 'new-payment':
        $result = \Rebill\SDK\Models\Payment::get($data['payment']['id']);
        var_dump($result->toArray());
        break;

    case 'new-subscription':
        $result = \Rebill\SDK\Models\Payment::get($data['subscription']['id']);
        var_dump($result->toArray());
        break;

    case 'subscription-change-status':
        $result = \Rebill\SDK\Models\Subscription::get($data['billingScheduleId']);
        var_dump($result->toArray());
        break;

    case 'process-headsup':
        $result = \Rebill\SDK\Models\Subscription::get($data['id']);
        var_dump($result->toArray());
        break;

}