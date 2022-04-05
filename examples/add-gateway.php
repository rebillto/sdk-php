<?php

include('setting.inc.php');
/*
// MercadoPago
/*
$result = (new \Rebill\SDK\Models\GatewayMercadoPago)->setAttributes([
    'code' => 'xxxxx',  // Auth code of Mercadopago
    'appId' => 'xxxxx'  // appID of Mercadopago APP
])->add('AR'); //ISO2 of Country
var_dump($result->toArray());

// DLocal
$result = (new \Rebill\SDK\Models\GatewayDLocal)->setAttributes([
    'xLogin' => 'xxxxx',
    'xTransKey' => 'xxxxx',
    'secretKey' => 'xxxxx'
])->add('US'); //ISO2 of Country
var_dump($result->toArray());

// Stripe
$result = (new \Rebill\SDK\Models\GatewayStripe)->setAttributes([
    'privateKey' => 'xxxxx',
    'publicKey' => 'xxxxx'
])->add('US'); //ISO2 of Country
var_dump($result->toArray());
*/