<?php

require('vendor/autoload.php');

\Rebill\SDK\Rebill::getInstance()->isDebug = true;
\Rebill\SDK\Rebill::getInstance()->setCallBackDebugLog(function ($data) {
    file_put_contents('logfile.log', '---------- '.date('c')." -------------- \n$data\n\n", FILE_APPEND | LOCK_EX);
});
\Rebill\SDK\Rebill::getInstance()->setProp([
    'sandbox' => true
]);
$result = (new \Rebill\SDK\Models\MerchantSignup)->setAttributes([
    'user' => (new \Rebill\SDK\Models\Shared\User)->setAttributes([
        'email' => 'test_user_2222@testuser.com',
        'password' => 'Qatest*2222',
    ]),
    'organization' => (new \Rebill\SDK\Models\Organization)->setAttributes([
        'name' => 'Unit Test',
        'alias' => 'unit-test',
        'address' => (new \Rebill\SDK\Models\Shared\Address)->setAttributes([
          'number' => '102',
          'street' => 'Calle A1',
          'floor' => '2',
          'apt' => 'B',
          'city' => 'Santa Cruz',
          'state' => 'Santa Cruz',
          'zipCode' => '9011',
          'country' => 'ARG',
          'description' => 'Home / Office'
        ])
    ])
])->create();

if ($result) {
    var_dump($result->toArray());
} else {
    echo 'Unk Error... See log file...';
}