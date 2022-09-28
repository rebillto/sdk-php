<?php

include('setting.inc.php');

/*
Obtener una Payment:

Se obtiene mediante el ID. El resultado sera un objeto del tipo \Rebill\SDK\Models\Payment

Los atributos están documentados aquí: https://docs.rebill.to/reference/paymentcontroller_getpaymentbyid-1

*/
$result = \Rebill\SDK\Models\Payment::get('07034a67-7c9d-406e-8b4d-8af4a9820b23');

var_dump($result->toArray());


/*
Si queremos obtener todos los Price ID procesados en este pago:
*/
$result->getPrices();
if (count($result->prices)) {
    foreach($result->prices as $id) {
        $price = \Rebill\SDK\Models\Price::get($id);
        var_dump($price->toArray());
    }
} else{
    echo 'error on get prices';
}


/*
Si queremos obtener todos los Subscription ID procesados en este pago:
*/
$result->getSubscriptions();
if (count($result->subscriptions)) {
    foreach($result->subscriptions as $id) {
        $sub = \Rebill\SDK\Models\Subscription::get($id);
        var_dump($sub->toArray());
    }
} else{
    echo 'error on get subscriptions';
}

/*
También podemos obtener un Payment con los datos de prices y subscriptions ya pre-cargados utilizando:

$payment = \Rebill\SDK\Models\Payment::get('07034a67-7c9d-406e-8b4d-8af4a9820b23')->getPrices()->getSubscriptions();

NOTA IMPORTANTE: Esto implicara 3 llamadas al API, utilizarlo solo de ser necesario.
*/


/*
Si queremos reembolsar un pago:
*/
if (!empty($result->id) && $result->refund()) {
    echo 'Refund OK';
} else{
    echo 'error on refund';
}