<?php

namespace Rebill\SDK\Tests\BasicTest;

use PHPUnit\Framework\TestCase;

/**
*  Test Rebill class
*
*  @author Kijam
*/
class IdentificationTypeTest extends TestCase
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
     * Check MercadoPago
     * @test
     */
    public function checkMercadoPago()
    {
        $result = \Rebill\SDK\Models\GatewayIdentificationTypes::get(
            'mercadopago',
            'AR'
        );
        $this->assertTrue($result && is_array($result) && count($result) > 0);
    }
    /**
     * Check DLocal
     * @test
     */
    public function checkDLocal()
    {
        $result = \Rebill\SDK\Models\GatewayIdentificationTypes::get(
            'dlocal',
            'AR'
        );
        $this->assertTrue($result && is_array($result) && count($result) > 0);
    }
    /**
     * Check Stripe
     * @test
     */
    public function checkStripe()
    {
        $result = \Rebill\SDK\Models\GatewayIdentificationTypes::get(
            'stripe',
            'AR'
        );
        $this->assertTrue($result && is_array($result) && count($result) > 0);
    }
}