<?php

include('setting.inc.php');
/*

Obtener los detalles de una Organización:

El resultado sera un objeto del tipo \Rebill\SDK\Models\Organization

Los atributos están documentados aquí: https://docs.rebill.to/reference/organizationcontroller_getorganization

*/
$result = \Rebill\SDK\Models\Organization::get();

var_dump($result->toArray());