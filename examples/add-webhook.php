<?php

include('setting.inc.php');

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
    'event' => 'subscription-charge-in-24-hours',
    'url' => 'http://webhook-test.rebll.to/capture-webhook.php?event=subscription-charge-in-24-hours'
])->create();

var_dump(\Rebill\SDK\Models\Webhook::all());