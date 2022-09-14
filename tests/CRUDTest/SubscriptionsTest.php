<?php

namespace Rebill\SDK\Tests\CRUDTest;

use PHPUnit\Framework\TestCase;

/**
*  Test Rebill class
*
*  @author Kijam
*/
class SubscriptionTest extends TestCase
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
    private function checkAllTest($checkout){
        $list_subs = [];
        $payments = [];
        if (isset($checkout->invoice) && ! empty( $checkout->invoice->id ) ){
            foreach($checkout->invoice->paidBags as $paid) {
                $payments[] = $paid->payment->id;
                foreach($paid->schedules as $subId) {
                    $list_subs[] = $subId;
                }
            }
        }
        if (isset($checkout->pendingTransaction) && ! empty( $checkout->pendingTransaction->id ) ){
            foreach($checkout->pendingTransaction->paidBags as $paid) {
                $payments[] = $paid->payment->id;
                foreach($paid->schedules as $subId) {
                    $list_subs[] = $subId;
                }
            }
        }
        foreach($payments as $id) {
            $payment = \Rebill\SDK\Models\Payment::get($id);
            if ($payment && !empty($payment->id)) {
                $this->assertTrue(true);
                $payment->getSubscriptions()->getPrices();
                $this->assertTrue(count($payment->prices) > 0, 'Prices in Payment '.$id.' not found');
                $this->assertTrue(count($payment->subscriptions) > 0, 'Prices in Payment '.$id.' not found');
                $this->assertTrue($payment->refund(), 'Refund Payment '.$id.' failed');
            } else {
                $this->assertTrue(false, 'Payment '.$id.' not found');
            }
        }
        foreach($list_subs as $id) {
            $subscription = \Rebill\SDK\Models\Subscription::get($id);
            if ($subscription && !empty($subscription->id)) {
                $this->assertTrue(true, 'Subscription is found');
                $subscription_edited                 = new \Rebill\SDK\Models\Subscription();
                $subscription_edited->id             = $subscription->id;
                $subscription_edited->card           = $subscription->card;
                $subscription_edited->amount         = $subscription->price->amount;
                $subscription_edited->nextChargeDate = $subscription->nextChargeDate;
                $subscription_edited->status         = 'PAUSED';
                if (! $subscription_edited->update() ) {
                    $this->assertTrue(false, 'Subscription '.$id.' fail to paused');
                } else {
                    $this->assertTrue(true);
                    $subscription_edited                 = new \Rebill\SDK\Models\Subscription();
                    $subscription_edited->id             = $subscription->id;
                    $subscription_edited->card           = $subscription->card;
                    $subscription_edited->amount         = $subscription->price->amount;
                    $subscription_edited->nextChargeDate = $subscription->nextChargeDate;
                    $subscription_edited->status         = 'ACTIVE';
                    $this->assertTrue(!!$subscription_edited->update(), 'Subscription '.$id.' fail to active');
                }
                $current_next = strtotime($subscription->nextChargeDate);
                $subscription_edited                 = new \Rebill\SDK\Models\Subscription();
                $subscription_edited->id             = $subscription->id;
                $subscription_edited->card           = $subscription->card;
                $subscription_edited->amount         = $subscription->price->amount;
                $subscription_edited->nextChargeDate = date('Y-m-d H:i:s', strtotime('+3 day', $current_next));
                $subscription_edited->status         = $subscription->status;
                $this->assertTrue(!!$subscription_edited->update(), 'Subscription '.$id.' update nextChargeDate fail');

                $price_id = $subscription->price->id;
                $price = \Rebill\SDK\Models\Price::get($price_id);
                
                if ($price && !empty($price->id)) {
                    $price_edited            = new \Rebill\SDK\Models\Price();
                    $price_edited->id        = $price->id;
                    $price_edited->amount    = '' . round( $price->amount * 1.25, 4 );
                    $price_edited->type      = $price->type;
                    $price_edited->frequency = $price->frequency;
                    if ( $price->freeTrial ) {
                        $price_edited->freeTrial = $price->freeTrial;
                    }
                    $price_edited->repetitions = $price->repetitions;
                    $price_edited->description = $price->description;
                    $price_edited->currency    = $price->currency;
                    $price_edited->gatewayId   = $price->gateway['id'];
                    if (! $price_edited->update() ) {
                        $this->assertTrue(false, 'Price '.$price_id.' update fail');
                    } else {
                        $subscription = \Rebill\SDK\Models\Subscription::get($id);
                        if (abs($subscription->price->amount - $price_edited->amount) > 0.0001 || $price_edited->id != $subscription->price->id) {
                            $this->assertTrue(false, 'Price '.$price_id.' amount update fail');
                        }
                    }
                } else {
                    $this->assertTrue(false, 'Price '.$price_id.' not found');
                }
                $subscription_edited                 = new \Rebill\SDK\Models\Subscription();
                $subscription_edited->id             = $subscription->id;
                $subscription_edited->card           = $subscription->card;
                $subscription_edited->amount         = '' . round( $subscription->price->amount * 1.11, 4 );
                $subscription_edited->nextChargeDate = $subscription->nextChargeDate;
                $subscription_edited->status         = $subscription->status;
                if (! $subscription_edited->update() ) {
                    $this->assertTrue(false, 'Subscription '.$id.' update amount fail');
                } else {
                    $this->assertTrue(true);
                    $new_subscription = \Rebill\SDK\Models\Subscription::get($id);
                    $this->assertTrue($new_subscription->price->id != $subscription->price->id, 'Price ID is equal before amount update');
                }
                $subscription_edited                 = new \Rebill\SDK\Models\Subscription();
                $subscription_edited->id             = $subscription->id;
                $subscription_edited->card           = $subscription->card;
                $subscription_edited->amount         = '' . $subscription->price->amount;
                $subscription_edited->nextChargeDate = $subscription->nextChargeDate;
                $subscription_edited->status         = 'CANCELLED';
                $this->assertTrue(!!$subscription_edited->update(), 'Subscription '.$id.' update cancelled fail');
            } else {
                $this->assertTrue(false, 'Subscription '.$id.' not found');
            }
        }
    }
    /**
     * Checkout and crud testing with dlocal
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
                'repetitions' => 5,
                'currency' => $currency,
                'gatewayId' => $g_id
            ]);
            $data_prices[] = (new \Rebill\SDK\Models\Price)->setAttributes([
                'amount' => '100.00',
                'type' => 'fixed',
                'description' => 'Example of Unique Payment for '.$g_name,
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
            'name' => 'Testing checkout DLocal',
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
                $this->checkAllTest($checkout);
                return;
            }
        }
        $this->assertTrue(false);
    }
    /**
     * Checkout and crud testing with stripe
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
                    'quantity' => 15
                ]),
                'repetitions' => 5,
                'currency' => $currency,
                'gatewayId' => $g_id
            ]);
            $data_prices[] = (new \Rebill\SDK\Models\Price)->setAttributes([
                'amount' => '100.00',
                'type' => 'fixed',
                'description' => 'Example of Unique Payment for '.$g_name,
                'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                    'type' => 'months',
                    'quantity' => 1
                ]),
                'repetitions' => null,
                'currency' => $currency,
                'gatewayId' => $g_id
            ]);
        }
        $result = (new \Rebill\SDK\Models\Item)->setAttributes([
            'name' => 'Testing checkout Stripe',
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
                $this->assertTrue(false, 'Stripe checkout is fail: '.implode(' - ', self::getErrorMessages($checkout)));
                return;
            }
            if (isset($checkout->invoice) && ! empty( $checkout->invoice->id ) || isset($checkout->pendingTransaction) && ! empty( $checkout->pendingTransaction->id ) ) {
                $this->assertTrue(true);
                $this->checkAllTest($checkout);
                return;
            }
        }
        $this->assertTrue(false);
    }
    /**
     * Checkout and crud testing with mercadopago
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
                'amount' => '9.00',
                'type' => 'fixed',
                'description' => 'Example of Unique Payment for '.$g_name,
                'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                    'type' => 'months',
                    'quantity' => 15
                ]),
                'repetitions' => 5,
                'currency' => $currency,
                'gatewayId' => $g_id
            ]);
            $data_prices[] = (new \Rebill\SDK\Models\Price)->setAttributes([
                'amount' => '5.00',
                'type' => 'fixed',
                'description' => 'Example of Unique Payment for '.$g_name,
                'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                    'type' => 'months',
                    'quantity' => 1
                ]),
                'repetitions' => null,
                'currency' => $currency,
                'gatewayId' => $g_id
            ]);
        }
        $result = (new \Rebill\SDK\Models\Item)->setAttributes([
            'name' => 'Testing checkout MercadoPago',
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
                $this->checkAllTest($checkout);
                return;
            }
        }
        $this->assertTrue(false);
    }
}