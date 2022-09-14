<?php

namespace Rebill\SDK\Tests\CheckoutTest;

use PHPUnit\Framework\TestCase;

/**
*  Test Rebill class
*
*  @author Kijam
*/
class OnePaymentCheckout extends TestCase
{
    public static $gateway = null;
    public static $currency = null;

    public static function setUpBeforeClass(): void
    {
        include(dirname(__FILE__).'/../setting.inc.php');
        self::$gateway = GATEWAYS_ID;
        self::$currency = GATEWAYS_CURRENCY;
    }
    private static function getErrorMessages($checkout){
        $errors = [];
        if (isset($checkout->failedTransaction) && ! empty( $checkout->failedTransaction->id ) ){
            foreach($checkout->failedTransaction->paidBags as $paid) {
                $errors[] = $paid->payment->status.': '.$paid->payment->errorMessage;
            }
        }
        return $errors;
    }
    /**
     * Checkout with dlocal
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
        }
        $result = (new \Rebill\SDK\Models\Item)->setAttributes([
            'name' => 'Testing checkout onetime DLocal',
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
            'customer' => (new \Rebill\SDK\Models\Shared\CheckoutCustomer)->setAttributes([
                'email' => MP_CUSTOMER_EMAIL,
                'firstName' => 'APRO Test',
                'lastName' => 'Name',
                'phone' => [
                    "countryCode" => "-","areaCode" => "-","phoneNumber" => "302390203929039"
                ],
                'address' => [
                    "street" => "Cale","state" => "Buenos Aires","city" => "San Isidro","country" => "AR","zipCode" => "2000"
                ],
                'taxId' => [
                    'type' => 'DNI',
                    'value' => ''.time()
                ],
                'personalId' => [
                    'type' => 'DNI',
                    'value' => ''.time()
                ],
                'card' => (new \Rebill\SDK\Models\Card)->setAttributes([
                    'cardNumber' => '4242424242424242',
                    'cardHolder' => [
                        'name' => 'APRO Test Name',
                        'identification' => [
                            'type' => 'DNI',
                            'value' => ''.time()
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
            if (isset( $checkout->failedTransaction ) && ! empty( $checkout->failedTransaction->id ) ) {
                $this->assertTrue(false, 'DLocal checkout is fail: '.implode(' - ', self::getErrorMessages($checkout)));
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
     * Checkout with installment and dlocal
     * @test
     */
    public function DLocalCheckoutWithInstallments()
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
        }
        $result = (new \Rebill\SDK\Models\Item)->setAttributes([
            'name' => 'Testing installment onetime DLocal',
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
        $installments = \Rebill\SDK\Models\Checkout::installments('450995', $prices[0]);
        if (!$installments || !count($installments)) {
            $this->assertTrue(false, 'DLocal installments is fail, this return: '.\var_export($installments, true));
            return;
        }
        $this->assertTrue(true);
        $last_installments = end($installments);
        $checkout = (new \Rebill\SDK\Models\Checkout)->setAttributes([
            'prices' => $prices,
            'installments' => (new \Rebill\SDK\Models\Shared\CheckoutInstallment)->setAttributes([
                'id' => $last_installments->id,
                'quantity' => $last_installments->quantity
            ]),
            'customer' => (new \Rebill\SDK\Models\Shared\CheckoutCustomer)->setAttributes([
                'email' => MP_CUSTOMER_EMAIL,
                'firstName' => 'APRO Test',
                'lastName' => 'Name',
                'phone' => [
                    "countryCode" => "-","areaCode" => "-","phoneNumber" => "302390203929039"
                ],
                'address' => [
                    "street" => "Cale","state" => "Buenos Aires","city" => "San Isidro","country" => "AR","zipCode" => "2000"
                ],
                'taxId' => [
                    'type' => 'DNI',
                    'value' => ''.time()
                ],
                'personalId' => [
                    'type' => 'DNI',
                    'value' => ''.time()
                ],
                'card' => (new \Rebill\SDK\Models\Card)->setAttributes([
                    'cardNumber' => '4509953566233704',
                    'cardHolder' => [
                        'name' => 'APRO Test Name',
                        'identification' => [
                            'type' => 'DNI',
                            'value' => ''.time()
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
            if (isset( $checkout->failedTransaction ) && ! empty( $checkout->failedTransaction->id ) ) {
                $this->assertTrue(false, 'DLocal checkout installment is fail: '.implode(' - ', self::getErrorMessages($checkout)));
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
     * Checkout with stripe
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
        }
        $result = (new \Rebill\SDK\Models\Item)->setAttributes([
            'name' => 'Testing checkout onetime Stripe',
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
            'customer' => (new \Rebill\SDK\Models\Shared\CheckoutCustomer)->setAttributes([
                'email' => MP_CUSTOMER_EMAIL,
                'firstName' => 'APRO Test',
                'lastName' => 'Name',
                'phone' => [
                    "countryCode" => "-","areaCode" => "-","phoneNumber" => "302390203929039"
                ],
                'address' => [
                    "street" => "Cale","state" => "Buenos Aires","city" => "San Isidro","country" => "AR","zipCode" => "2000"
                ],
                'taxId' => [
                    'type' => 'DNI',
                    'value' => ''.time()
                ],
                'personalId' => [
                    'type' => 'DNI',
                    'value' => ''.time()
                ],
                'card' => (new \Rebill\SDK\Models\Card)->setAttributes([
                    'cardNumber' => '4242424242424242',
                    'cardHolder' => [
                        'name' => 'APRO Test Name',
                        'identification' => [
                            'type' => 'DNI',
                            'value' => ''.time()
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
            if (isset($checkout->failedTransaction) && ! empty($checkout->failedTransaction->id)) {
                $this->assertTrue(false, 'Stripe checkout is fail: '.implode(' - ', self::getErrorMessages($checkout)));
                return;
            }
            if (isset($checkout->invoice) && ! empty($checkout->invoice->id) || isset($checkout->pendingTransaction) && ! empty($checkout->pendingTransaction->id)) {
                $this->assertTrue(true);
                return;
            }
        }
        $this->assertTrue(false);
    }
    /**
     * Checkout with mercadopago
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
                'amount' => '15.00',
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
        }
        $result = (new \Rebill\SDK\Models\Item)->setAttributes([
            'name' => 'Testing checkout onetime MP',
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
            'customer' => (new \Rebill\SDK\Models\Shared\CheckoutCustomer)->setAttributes([
                'email' => MP_CUSTOMER_EMAIL,
                'firstName' => 'APRO Test',
                'lastName' => 'Name',
                'phone' => [
                    "countryCode" => "-","areaCode" => "-","phoneNumber" => "302390203929039"
                ],
                'address' => [
                    "street" => "Cale","state" => "Buenos Aires","city" => "San Isidro","country" => "AR","zipCode" => "2000", "number" => "0"
                ],
                'taxId' => [
                    'type' => 'DNI',
                    'value' => ''.time()
                ],
                'personalId' => [
                    'type' => 'DNI',
                    'value' => ''.time()
                ],
                'card' => (new \Rebill\SDK\Models\Card)->setAttributes([
                    'cardNumber' => '4509953566233704',
                    'cardHolder' => [
                        'name' => 'APRO Test Name',
                        'identification' => [
                            'type' => 'DNI',
                            'value' => ''.time()
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
            if (isset( $checkout->failedTransaction ) && ! empty( $checkout->failedTransaction->id ) ) {
                $this->assertTrue(false, 'Mercadopago checkout is fail: '.implode(' - ', self::getErrorMessages($checkout)));
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
     * Checkout with installment and mercadopago
     * @test
     */
    public function MercadoPagoCheckoutWithInstallments()
    {
        $data_prices = array();
        foreach (self::$gateway as $g_name => $g_id) {
            if ($g_name != 'mercadopago') {
                continue;
            }
            $currency = self::$currency[$g_name];
            $data_prices[] = (new \Rebill\SDK\Models\Price)->setAttributes([
                'amount' => '5000.00',
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
        }
        $result = (new \Rebill\SDK\Models\Item)->setAttributes([
            'name' => 'Testing checkout onetime MP',
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
        
        $installments = \Rebill\SDK\Models\Checkout::installments('450995', $prices[0]);
        if (!$installments || !count($installments)) {
            $this->assertTrue(false, 'MercadoPago installments is fail, this return: '.\var_export($installments, true));
            return;
        }
        $this->assertTrue(true);
        $last_installments = end($installments);
        $checkout = (new \Rebill\SDK\Models\Checkout)->setAttributes([
            'prices' => $prices,
            'installments' => (new \Rebill\SDK\Models\Shared\CheckoutInstallment)->setAttributes([
                'id' => $last_installments->id,
                'quantity' => $last_installments->quantity
            ]),
            'customer' => (new \Rebill\SDK\Models\Shared\CheckoutCustomer)->setAttributes([
                'email' => MP_CUSTOMER_EMAIL,
                'firstName' => 'APRO Test',
                'lastName' => 'Name',
                'phone' => [
                    "countryCode" => "-","areaCode" => "-","phoneNumber" => "302390203929039"
                ],
                'address' => [
                    "street" => "Cale","state" => "Buenos Aires","city" => "San Isidro","country" => "AR","zipCode" => "2000", "number" => "0"
                ],
                'taxId' => [
                    'type' => 'DNI',
                    'value' => ''.time()
                ],
                'personalId' => [
                    'type' => 'DNI',
                    'value' => ''.time()
                ],
                'card' => (new \Rebill\SDK\Models\Card)->setAttributes([
                    'cardNumber' => '4509953566233704',
                    'cardHolder' => [
                        'name' => 'APRO Test Name',
                        'identification' => [
                            'type' => 'DNI',
                            'value' => ''.time()
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
            if (isset( $checkout->failedTransaction ) && ! empty( $checkout->failedTransaction->id ) ) {
                $this->assertTrue(false, 'Mercadopago checkout is fail: '.implode(' - ', self::getErrorMessages($checkout)));
                return;
            }
            if (isset($checkout->invoice) && ! empty( $checkout->invoice->id ) || isset($checkout->pendingTransaction) && ! empty( $checkout->pendingTransaction->id ) ) {
                $this->assertTrue(true);
                return;
            }
        }
        $this->assertTrue(false);
    }
}
