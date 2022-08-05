<?php

namespace Rebill\SDK\Tests\CheckoutTest;

use PHPUnit\Framework\TestCase;

/**
*  Test Rebill class
*
*  @author Kijam
*/
class MixPaymentCheckout extends TestCase
{
    public static $gateway = null;
    public static $currency = null;

    public static function setUpBeforeClass(): void
    {
        include(dirname(__FILE__).'/../setting.inc.php');
        self::$gateway = GATEWAYS_ID;
        self::$currency = GATEWAYS_CURRENCY;
    }
    /**
     * Check Create - Item/Price
     * @test
     */
    public function DLocalCheckout()
    {
        $data_prices = array();
        foreach (self::$gateway as $g_name => $g_id) {
            if ($g_name != 'dlocal') {
                continue;
            }
            $currency = self::$currency[$g_name];
            $data_prices[] = (new \Rebill\SDK\Models\Price)->setAttributes([
                'amount' => '100.00',
                'type' => 'fixed',
                'description' => 'Example of Unique Payment for '.$g_name,
                'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                    'type' => 'months',
                    'quantity' => 1
                ]),
                'repetitions' => 1,
                'currency' => $currency,
                'gatewayId' => $g_id
            ]);
            $data_prices[] = (new \Rebill\SDK\Models\Price)->setAttributes([
                'amount' => '100.00',
                'type' => 'fixed',
                'description' => 'Example of cyclic Payment for '.$g_name,
                'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                    'type' => 'months',
                    'quantity' => 15
                ]),
                'repetitions' => 5,
                'currency' => $currency,
                'gatewayId' => $g_id
            ]);
            $data_prices[] = (new \Rebill\SDK\Models\Price)->setAttributes([
                'amount' => '100.00',
                'type' => 'fixed',
                'description' => 'Example of recurrent Payment for '.$g_name,
                'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                    'type' => 'months',
                    'quantity' => 15
                ]),
                'repetitions' => null,
                'currency' => $currency,
                'gatewayId' => $g_id
            ]);
        }
        $result = (new \Rebill\SDK\Models\Item)->setAttributes([
            'name' => 'Testing checkout mix DLocal',
            'description' => 'Test of Checkout',
            'metadata' => [
                'key_of_meta1' => 'example meta 1',
                'key_of_meta2' => 'example meta 2',
            ],
            'prices' => $data_prices
        ])->create();
        $prices = [];
        foreach ($result->prices as $p) {
            $prices[] = (new \Rebill\SDK\Models\Shared\CheckoutPrice)->setAttributes([
                'id' => $p->id,
                'quantity' => 1
            ]);
        }
        $checkout = (new \Rebill\SDK\Models\Checkout)->setAttributes([
            'prices' => $prices,
            'customer' => (new \Rebill\SDK\Models\Shared\CustomerCheckout)->setAttributes([
                'email' => 'usertest@test.com',
                'firstName' => 'Test',
                'lastName' => 'Name',
                'phone' => [
                    "countryCode" => "","areaCode" => "","phoneNumber" => "87689"
                ],
                'address' => [
                    "street" => "Cale","state" => "Buenos Aires","city" => "San Isidro","country" => "AR","zipCode" => "1000"
                ],
                'taxId' => [
                    "type" => "Other","value" => "87876899"
                ],
                'card' => (new \Rebill\SDK\Models\Card)->setAttributes([
                    'cardNumber' => '4111111111111111',
                    'cardHolder' => [
                        'name' => 'Test Card',
                        'identification' => [
                            'type' => 'DNI',
                            'value' => '1111111111'
                        ]
                    ],
                    'securityCode' => '123',
                    'expiration' => [
                        'month' => 12,
                        'year' => 2030
                    ],
                ])
            ])
        ])->create();
        if ($checkout) {
            if (isset($checkout->failedTransaction)) {
                $this->assertTrue(false);
                return;
            }
            if (isset($checkout->invoice) || isset($checkout->pendingTransaction)) {
                $this->assertTrue(true);
                return;
            }
        }
        $this->assertTrue(false);
    }
    /**
     * Check Create - Item/Price
     * @test
     */
    public function StripeCheckout()
    {
        $data_prices = array();
        foreach (self::$gateway as $g_name => $g_id) {
            if ($g_name != 'stripe') {
                continue;
            }
            $currency = self::$currency[$g_name];
            $data_prices[] = (new \Rebill\SDK\Models\Price)->setAttributes([
                'amount' => '100.00',
                'type' => 'fixed',
                'description' => 'Example of Unique Payment for '.$g_name,
                'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                    'type' => 'months',
                    'quantity' => 1
                ]),
                'repetitions' => 1,
                'currency' => $currency,
                'gatewayId' => $g_id
            ]);
            $data_prices[] = (new \Rebill\SDK\Models\Price)->setAttributes([
                'amount' => '100.00',
                'type' => 'fixed',
                'description' => 'Example of cyclic Payment for '.$g_name,
                'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                    'type' => 'months',
                    'quantity' => 15
                ]),
                'repetitions' => 5,
                'currency' => $currency,
                'gatewayId' => $g_id
            ]);
            $data_prices[] = (new \Rebill\SDK\Models\Price)->setAttributes([
                'amount' => '100.00',
                'type' => 'fixed',
                'description' => 'Example of recurrent Payment for '.$g_name,
                'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                    'type' => 'months',
                    'quantity' => 15
                ]),
                'repetitions' => null,
                'currency' => $currency,
                'gatewayId' => $g_id
            ]);
        }
        $result = (new \Rebill\SDK\Models\Item)->setAttributes([
            'name' => 'Testing checkout mix stripe',
            'description' => 'Test of Checkout',
            'metadata' => [
                'key_of_meta1' => 'example meta 1',
                'key_of_meta2' => 'example meta 2',
            ],
            'prices' => $data_prices
        ])->create();
        $prices = [];
        foreach ($result->prices as $p) {
            $prices[] = (new \Rebill\SDK\Models\Shared\CheckoutPrice)->setAttributes([
                'id' => $p->id,
                'quantity' => 1
            ]);
        }
        $checkout = (new \Rebill\SDK\Models\Checkout)->setAttributes([
            'prices' => $prices,
            'customer' => (new \Rebill\SDK\Models\Shared\CustomerCheckout)->setAttributes([
                'email' => 'usertest@test.com',
                'firstName' => 'Test',
                'lastName' => 'Name',
                'phone' => [
                    "countryCode" => "-","areaCode" => "-","phoneNumber" => "87689"
                ],
                'address' => [
                    "street" => "Cale","state" => "Buenos Aires","city" => "San Isidro","country" => "AR","zipCode" => "1000"
                ],
                'taxId' => [
                    "type" => "Other","value" => "87876899"
                ],
                'card' => (new \Rebill\SDK\Models\Card)->setAttributes([
                    'cardNumber' => '4242424242424242',
                    'cardHolder' => [
                        'name' => 'Test Card',
                        'identification' => [
                            'type' => 'DNI',
                            'value' => '1111111111'
                        ]
                    ],
                    'securityCode' => '123',
                    'expiration' => [
                        'month' => 12,
                        'year' => 2030
                    ],
                ])
            ])
        ])->create();
        if ($checkout) {
            if (isset( $checkout->failedTransaction ) && ! empty( $checkout->failedTransaction->id ) ) {
                $this->assertTrue(false);
                return;
            }
            if (isset($checkout->invoice) && ! empty( $checkout->invoice->id ) || isset($checkout->pendingTransaction) && ! empty( $checkout->pendingTransaction->id ) ) {
                $this->assertTrue(true);
                return;
            }
        }
        $this->assertTrue(false);
    }
    /**
     * Check Create - Item/Price
     * @test
     */
    public function MercadoPagoCheckout()
    {
        $data_prices = array();
        foreach (self::$gateway as $g_name => $g_id) {
            if ($g_name != 'mercadopago') {
                continue;
            }
            $currency = self::$currency[$g_name];
            $data_prices[] = (new \Rebill\SDK\Models\Price)->setAttributes([
                'amount' => '4.00',
                'type' => 'fixed',
                'description' => 'Example of Unique Payment for '.$g_name,
                'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                    'type' => 'months',
                    'quantity' => 1
                ]),
                'repetitions' => 1,
                'currency' => $currency,
                'gatewayId' => $g_id
            ]);
            $data_prices[] = (new \Rebill\SDK\Models\Price)->setAttributes([
                'amount' => '5.00',
                'type' => 'fixed',
                'description' => 'Example of cyclic Payment for '.$g_name,
                'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                    'type' => 'months',
                    'quantity' => 15
                ]),
                'repetitions' => 5,
                'currency' => $currency,
                'gatewayId' => $g_id
            ]);
            $data_prices[] = (new \Rebill\SDK\Models\Price)->setAttributes([
                'amount' => '8.00',
                'type' => 'fixed',
                'description' => 'Example of Recurrent Payment for '.$g_name,
                'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                    'type' => 'months',
                    'quantity' => 15
                ]),
                'repetitions' => null,
                'currency' => $currency,
                'gatewayId' => $g_id
            ]);
        }
        $result = (new \Rebill\SDK\Models\Item)->setAttributes([
            'name' => 'Testing checkout mix Mercadopago',
            'description' => 'Test of Checkout',
            'metadata' => [
                'key_of_meta1' => 'example meta 1',
                'key_of_meta2' => 'example meta 2',
            ],
            'prices' => $data_prices
        ])->create();
        $prices = [];
        foreach ($result->prices as $p) {
            $prices[] = (new \Rebill\SDK\Models\Shared\CheckoutPrice)->setAttributes([
                'id' => $p->id,
                'quantity' => 1
            ]);
        }
        $checkout = (new \Rebill\SDK\Models\Checkout)->setAttributes([
            'prices' => $prices,
            'customer' => (new \Rebill\SDK\Models\Shared\CustomerCheckout)->setAttributes([
                'email' => MP_CUSTOMER_EMAIL,
                'firstName' => 'APRO',
                'lastName' => 'APRO',
                'phone' => [
                    "countryCode" => "-","areaCode" => "-","phoneNumber" => "87689"
                ],
                'address' => [
                    "street" => "Cale",
                    "state" => "Buenos Aires",
                    "city" => "San Isidro",
                    "country" => "AR",
                    "zipCode" => "1000",
                    "number" => "0"
                ],
                'taxId' => [
                    "type" => "Other","value" => "87876899"
                ],
                'card' => (new \Rebill\SDK\Models\Card)->setAttributes([
                    'cardNumber' => '4509953566233704',
                    'cardHolder' => [
                        'name' => 'Test Card',
                        'identification' => [
                            'type' => 'DNI',
                            'value' => '1111111111'
                        ]
                    ],
                    'securityCode' => '123',
                    'expiration' => [
                        'month' => 11,
                        'year' => 2025
                    ],
                ])
            ])
        ])->create();
        if ($checkout) {
            if (isset($checkout->failedTransaction)) {
                $this->assertTrue(false);
                return;
            }
            if (isset($checkout->invoice) || isset($checkout->pendingTransaction)) {
                $this->assertTrue(true);
                return;
            }
        }
        $this->assertTrue(false);
    }
}
