<?php

namespace Rebill\SDK\Tests\CRUDTest;

use PHPUnit\Framework\TestCase;

/**
*  Test Rebill class
*
*  @author Kijam
*/
class PricesTest extends TestCase
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
    public function newItemPrice()
    {
        $result = (new \Rebill\SDK\Models\Item)->setAttributes([
            'name' => 'Fixed Item',
            'description' => 'Example of Fixed Item',
            'prices' => [
                (new \Rebill\SDK\Models\Price)->setAttributes([
                    'amount' => "2.5",
                    'type' => 'fixed',
                    'description' => 'Example of Price Fixed',
                    'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                        'type' => 'months',
                        'quantity' => 2
                    ]),
                    'freeTrial' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                        'type' => 'months',
                        'quantity' => 1
                    ]),
                    'repetitions' => null, // Infinite repetition
                    'currency' => self::$currency['dlocal'],
                    'gatewayId' => self::$gateway['dlocal']
                ])
            ]
        ])->create();
        $this->assertTrue($result && !empty($result->item->id) && count($result->prices) == 1);
    }
    /**
     * Check Create complex Item/Price
     * @test
     */
    public function newComplexItemPrice()
    {
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
                    'description' => 'Example of Price tiered',
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
                        'type' => 'months',
                        'quantity' => 2
                    ]),
                    'freeTrial' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                        'type' => 'months',
                        'quantity' => 1
                    ]),
                    'repetitions' => 2,
                    'currency' => self::$currency['dlocal'],
                    'gatewayId' => self::$gateway['dlocal']
                ])
            ]
        ])->create();
        $this->assertTrue($result && !empty($result->item->id) && count($result->prices) == 1);
    }
    /**
     * Check Create - Item/Price
     * @test
     */
    public function addPriceToEmptyItem()
    {
        $result = (new \Rebill\SDK\Models\Item)->setAttributes([
            'name' => 'Empty Item',
            'description' => 'Example of Empty Item',
            'prices' => [ ]
        ])->create();
        if ($result && !empty($result->item->id) && count($result->prices) == 0) {
            $price1 = ( new \Rebill\SDK\Models\Price() )->setAttributes([
                    'amount' => "2.5",
                    'type' => 'fixed',
                    'description' => 'Example of Price Fixed',
                    'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                        'type' => 'months',
                        'quantity' => 2
                    ]),
                    'freeTrial' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                        'type' => 'months',
                        'quantity' => 1
                    ]),
                    'repetitions' => null, // Infinite repetition
                    'currency' => self::$currency['dlocal'],
                    'gatewayId' => self::$gateway['dlocal']
            ])->add($result->item->id);
            $price2 = ( new \Rebill\SDK\Models\Price() )->setAttributes([
                    'amount' => "2.5",
                    'type' => 'fixed',
                    'description' => 'Example of Price Fixed',
                    'frequency' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                        'type' => 'months',
                        'quantity' => 2
                    ]),
                    'freeTrial' => (new \Rebill\SDK\Models\Shared\Frequency)->setAttributes([
                        'type' => 'months',
                        'quantity' => 1
                    ]),
                    'repetitions' => null, // Infinite repetition
                    'currency' => self::$currency['dlocal'],
                    'gatewayId' => self::$gateway['dlocal']
            ])->add($result->item->id);
            $this->assertTrue($price1 && !empty($price1->id) && $price2 && !empty($price2->id));
            return;
        }
        $this->assertTrue(false);
    }
}
