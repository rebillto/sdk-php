<?php

include('setting.inc.php');

$result = (new \Rebill\SDK\Models\Organization)->get();

var_dump($result->toArray());