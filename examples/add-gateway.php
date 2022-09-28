<?php

include('setting.inc.php');

/*
Gateway:

Para crear un gateway se recomienda utilizar https://dashboard.rebill.to/, también se puede utilizar el SDK
con los siguientes tipo de objetos:

\Rebill\SDK\Models\GatewayMercadoPago -> https://docs.rebill.to/reference/organizationcontroller_addstandardmercadopagokeys
\Rebill\SDK\Models\GatewayDLocal -> https://docs.rebill.to/reference/organizationcontroller_connecttodlocal
\Rebill\SDK\Models\GatewayStripe -> https://docs.rebill.to/reference/organizationcontroller_connecttostripe

*/

// MercadoPago
$result = (new \Rebill\SDK\Models\GatewayMercadoPago)->setAttributes([
    'code' => 'xxxxx',  // Auth code of Mercadopago
    'appId' => 'xxxxx',  // appID of Mercadopago APP
    'description' => 'Test MP'
])->add('AR'); //ISO2 of Country
var_dump($result->toArray());

// DLocal
$result = (new \Rebill\SDK\Models\GatewayDLocal)->setAttributes([
    'xLogin' => 'xxxxx',
    'xTransKey' => 'xxxxx',
    'secretKey' => 'xxxxx',
    'description' => 'Test DLocal'
])->add('BO'); //ISO2 of Country
var_dump($result->toArray());

// Stripe
$result = (new \Rebill\SDK\Models\GatewayStripe)->setAttributes([
    'privateKey' => 'xxxxx',
    'publicKey' => 'xxxxx',
    'description' => 'Test Stripe'
])->add('US'); //ISO2 of Country
var_dump($result->toArray());
