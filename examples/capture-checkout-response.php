<?php

include('setting.inc.php');

/*
Capturar un Checkout desde un request del Client SDK JS (https://support.rebill.to/es/articles/6062873-como-utilizar-sdk-v2)

El resultado de un checkout sera del tipo: \Rebill\SDK\Models\Response\CheckoutResponse

Los atributos están definidos en https://docs.rebill.to/reference/checkoutcontroller_docheckout
*/

$json = file_get_contents('php://input');

file_put_contents('checkout.log', '---------- '.date('c')." -------------- \n\nPOST: $json \n\n", FILE_APPEND | LOCK_EX);

$data = json_decode($json, true);

$checkout = (new \Rebill\SDK\Models\Response\CheckoutResponse)->setAttributes($data);
/*
    Dependiendo del tipo de respuesta se obtiene el status del pago.
    Cada pago generado se retornara en un arreglo de paidBags definidos
    en el objeto Rebill\SDK\Models\Shared\PaidBag
*/
if (isset($checkout->invoice) && !empty($checkout->invoice->id)) {
    echo "Payment OK:\n";
    var_dump($checkout->invoice->paidBags);
    foreach($checkout->invoice->paidBags as $paid) {
        $payment = \Rebill\SDK\Models\Payment::get($paid->payment->id);
        if (empty($payment->id)) {
            die('Phishing?...');
        }
        // Procesar pago...

        // Recomendamos verificar si el $payment->id no fue procesado anteriormente y sea
        // un pago realmente nuevo.
    }
}
if (isset($checkout->pendingTransaction) && !empty($checkout->pendingTransaction->id)) {
    echo "Payment Pending:\n";
    var_dump($checkout->pendingTransaction->paidBags);
    foreach($checkout->pendingTransaction->paidBags as $paid) {
        $payment = \Rebill\SDK\Models\Payment::get($paid->payment->id);
        if (empty($payment->id)) {
            die('Phishing?...');
        }
        // Procesar pago...

        // Recomendamos verificar si el $payment->id no fue procesado anteriormente y sea
        // un pago realmente nuevo.
    }
}
if (isset($checkout->failedTransaction) && !empty($checkout->failedTransaction->id)) {
    echo "Payment Error:\n";
    foreach($checkout->failedTransaction->paidBags as $paid) {
        echo $paid->payment->status.': '.$paid->payment->errorMessage."\n";
    }
}