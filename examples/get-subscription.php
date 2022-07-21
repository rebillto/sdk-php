<?php

include('setting.inc.php');

$result = \Rebill\SDK\Models\Subscription::get('9da3e4ac-44a7-495d-b03d-fc81c9ebcf23');

var_dump($result->toArray());
