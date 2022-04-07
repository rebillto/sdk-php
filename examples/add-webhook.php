<?php

include('setting.inc.php');

$webhooks     = \Rebill\SDK\Models\Webhook::all();
if ( $webhooks && is_array( $webhooks ) && count( $webhooks ) ) {
    foreach ( $webhooks as $webhook ) {
        $webhook->delete();
    }
}

$result = (new \Rebill\SDK\Models\Webhook)->setAttributes([
    'event' => 'one-time-charge',
    'url' => 'http://webhook-test.rebll.to/capture-webhook.php?event=one-time-charge'
])->create();
$result = (new \Rebill\SDK\Models\Webhook)->setAttributes([
    'event' => 'subscription-charge',
    'url' => 'http://webhook-test.rebll.to/capture-webhook.php?event=subscription-charge'
])->create();
$result = (new \Rebill\SDK\Models\Webhook)->setAttributes([
    'event' => 'subscription_charge_in_24_hours',
    'url' => 'http://webhook-test.rebll.to/capture-webhook.php?event=subscription_charge_in_24_hours'
])->create();
$result = (new \Rebill\SDK\Models\Webhook)->setAttributes([
    'event' => 'one-time-charge-change-status',
    'url' => 'http://webhook-test.rebll.to/capture-webhook.php?event=one-time-charge-change-status'
])->create();
$result = (new \Rebill\SDK\Models\Webhook)->setAttributes([
    'event' => 'subscription-charge-change-status',
    'url' => 'http://webhook-test.rebll.to/capture-webhook.php?event=subscription-charge-change-status'
])->create();

var_dump(\Rebill\SDK\Models\Webhook::all());