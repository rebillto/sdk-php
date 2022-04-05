<?php

$get = var_export($_GET, true);
$json = file_get_contents('php://input');

file_put_contents('webhook.log', '---------- '.date('c')." -------------- \n\nGET: $get \n\nPOST: $json \n\n", FILE_APPEND | LOCK_EX);