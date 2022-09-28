<?php

include('setting.inc.php');
/*

Obtener los tipos de documentos validos para un Gateway y un País

El resultado sera un objeto del tipo \Rebill\SDK\Models\GatewayIdentificationType

Los atributos están documentados aquí: https://docs.rebill.to/reference/organizationcontroller_getorganization

Parámetros validos:
    1er Argumento: mercadopago|dlocal|stripe
    2do Argumento: ISO2 del país del cliente
*/
$result = \Rebill\SDK\Models\GatewayIdentificationTypes::get('mercadopago', 'ar');

var_dump($result);

$result = \Rebill\SDK\Models\GatewayIdentificationTypes::get('mercadopago', 'uk');

var_dump($result);