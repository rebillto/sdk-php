<?php

include('setting.inc.php');

$result = (new \Rebill\SDK\Models\Item)->setAttributes([
    'name' => 'Fixed Item',
    'description' => 'Example of Fixed Item',
    'prices' => [
        (new \Rebill\SDK\Models\Price)->setAttributes([
            'amount' => "2.5",
            'type' => 'fixed',
            'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                'type' => 'days',
                'quantity' => 2
            ]),
            'freeTrial' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                'type' => 'days',
                'quantity' => 2
            ]),
            'repetitions' => 2,
            'currency' => 'USD',
            'description' => 'Example of Price Fixed',
            'gatewayId' => '9f32cfd8-b302-4c98-ac99-16996e35f466'
        ])
    ]
])->create();

var_dump($result->toArray());

$result = (new \Rebill\SDK\Models\Item)->setAttributes([
    'name' => 'Tiered Item',
    'description' => 'Example of tiered Item',
    'metadata' => [
        'external_reference' => 'dummy value',
        'other_meta' => 'other dummy value'
    ],
    'prices' => [
        (new \Rebill\SDK\Models\Price)->setAttributes([
            'type' => 'tiered',
            'tiers' => [
                (new \Rebill\SDK\Models\Shared\Tiers)->setAttributes([
                    'amount' => "1.5",
                    'upTo' => 5,
                    'id' => '1231232311'
                ]),
                (new \Rebill\SDK\Models\Shared\Tiers)->setAttributes([
                    'amount' => "1",
                    'upTo' => 10,
                    'id' => '1231232322'
                ]),
            ],
            'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                'type' => 'days',
                'quantity' => 2
            ]),
            'freeTrial' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                'type' => 'days',
                'quantity' => 1
            ]),
            'repetitions' => 2,
            'currency' => 'USD',
            'description' => 'Example of Price tiered',
            'gatewayId' => '9f32cfd8-b302-4c98-ac99-16996e35f466'
        ])
    ]
])->create();

var_dump($result->toArray());



