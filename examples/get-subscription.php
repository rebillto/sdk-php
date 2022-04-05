<?php

include('setting.inc.php');

$result = (new \Rebill\SDK\Models\Subscription)->get('1ce64310-018a-45fd-8044-3b475b04e7cb');

var_dump($result->toArray());