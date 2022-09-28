<?php

include('setting.inc.php');

/*
Obtener una Suscripción:

Se obtiene mediante el ID. El resultado sera un objeto del tipo \Rebill\SDK\Models\Subscription

Los atributos están documentados aquí: https://docs.rebill.to/reference/subscriptioncontroller_getbillingschedulebyid

Posibles valores para status:
ACTIVE
PAUSED
CANCELLED

*/
$result = \Rebill\SDK\Models\Subscription::get('07034a67-7c9d-406e-8b4d-8af4a9820b23');

var_dump($result->toArray());

/*
Si queremos actualizar una Suscripción se debe crear un nuevo objeto y asignarle todos los atributos requeridos.
*/
$to_edit = new \Rebill\SDK\Models\Subscription;
$to_edit->id = $result->id; //ID de la suscripción que vamos a modificar
$to_edit->card = $result->card;
$to_edit->amount = $result->price->amount;
$to_edit->status = 'PAUSED';
$to_edit->nextChargeDate = $result->nextChargeDate;
if ($to_edit->update()) {
    var_dump($result);
} else{
    echo 'error on update';
}