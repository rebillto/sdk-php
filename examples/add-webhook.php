<?php

include('setting.inc.php');
/*
Webhooks:
Elimina o crea nuevos webhooks, los atributos están documentados en https://docs.rebill.to/reference/notificationscontroller_create
*/
$webhooks     = \Rebill\SDK\Models\Webhook::all();
if ( $webhooks && is_array( $webhooks ) && count( $webhooks ) ) {
    foreach ( $webhooks as $webhook ) {
        $webhook->delete();
    }
}

$result = (new \Rebill\SDK\Models\Webhook)->setAttributes([
    'event' => 'new-subscription',
    'url' => 'http://webhook-test.rebll.to/capture-webhook.php?event=new-subscription'
])->create();
$result = (new \Rebill\SDK\Models\Webhook)->setAttributes([
    'event' => 'new-payment',
    'url' => 'http://webhook-test.rebll.to/capture-webhook.php?event=new-payment'
])->create();
$result = (new \Rebill\SDK\Models\Webhook)->setAttributes([
    'event' => 'payment-change-status',
    'url' => 'http://webhook-test.rebll.to/capture-webhook.php?event=payment-change-status'
])->create();
$result = (new \Rebill\SDK\Models\Webhook)->setAttributes([
    'event' => 'subscription-change-status',
    'url' => 'http://webhook-test.rebll.to/capture-webhook.php?event=subscription-change-status'
])->create();
$result = (new \Rebill\SDK\Models\Webhook)->setAttributes([
    'event' => 'process-headsups',
    'url' => 'http://webhook-test.rebll.to/capture-webhook.php?event=process-headsup'
])->create();

var_dump(\Rebill\SDK\Models\Webhook::all());