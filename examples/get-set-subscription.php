<?php

include('setting.inc.php');

$result = \Rebill\SDK\Models\Subscription::get('07034a67-7c9d-406e-8b4d-8af4a9820b23');

var_dump($result->toArray());

$result->card = $result->card;
$result->amount = $result->price->amount;
$result->status = $result->status;
$result->nextChargeDate = '2022-06-09T14:55:00.000Z';
$result->update();
var_dump($result);