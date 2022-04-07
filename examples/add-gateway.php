<?php

include('setting.inc.php');
/*

$result = (new \Rebill\SDK\Models\GatewayStripe)->setAttributes([
    'publicKey' => 'pk_test_51JtzifLdmyGpZQ0WKPbalEwbxhKW4AzovPGaEeFMB5YYmsRNht4yrbcfr4AYQfIBpuegbSCSEVIztiqget6cWw7j00uia3Q5T5',
    'privateKey' => 'sk_test_51JtzifLdmyGpZQ0WS33zTOCsE5VvB0PY4J0KuTlge30lsEaEjHNwVuxWgqJoDjoqZv5ZBDn0CdsvWOKCYiiCGvUE00fKgYivQ4'
])->add('US'); //ISO2 of Country
*/
$result = (new \Rebill\SDK\Models\GatewayDLocal)->setAttributes([
    'xLogin' => 'jmysVv9qiy',
    'xTransKey' => 'sPYqiR4y9O',
    'secretKey' => 'nsCV7wBdeOryePld4QrC0g9CuegKWk1M2',
    'description' => 'Test DLocal'
])->add('BO'); //ISO2 of Country
var_dump($result->toArray());
// MercadoPago
/*
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
])->add('US'); //ISO2 of Country
var_dump($result->toArray());

// Stripe
$result = (new \Rebill\SDK\Models\GatewayStripe)->setAttributes([
    'privateKey' => 'xxxxx',
    'publicKey' => 'xxxxx',
    'description' => 'Test Stripe'
])->add('US'); //ISO2 of Country
var_dump($result->toArray());
*/