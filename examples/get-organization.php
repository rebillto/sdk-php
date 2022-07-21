<?php

include('setting.inc.php');

$result = \Rebill\SDK\Models\Organization::get();

var_dump($result->toArray());