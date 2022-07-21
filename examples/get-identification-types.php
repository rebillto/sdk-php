<?php

include('setting.inc.php');

$result = \Rebill\SDK\Models\GatewayIdentificationTypes::get('mercadopago', 'ar');

var_dump($result);

$result = \Rebill\SDK\Models\GatewayIdentificationTypes::get('mercadopago', 'uk');

var_dump($result);