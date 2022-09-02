<?php

namespace Rebill\SDK\Tests\CRUDTest;

use PHPUnit\Framework\TestCase;

/**
*  Test Rebill class
*
*  @author Kijam
*/
class WebhookTest extends TestCase
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
     * Check CRUD Webhook
     * @test
     */
    public function crud()
    {
        $webhooks     = \Rebill\SDK\Models\Webhook::all();
        if ($webhooks && is_array($webhooks) && count($webhooks)) {
            foreach ($webhooks as $webhook) {
                $webhook->delete();
            }
            $webhooks     = \Rebill\SDK\Models\Webhook::all();
            if ($webhooks && is_array($webhooks) && count($webhooks) > 0) {
                $this->assertTrue(false, 'Fail first delete all');
                return;
            }
        }
        $result = (new \Rebill\SDK\Models\Webhook)->setAttributes([
            'event' => 'new-subscription',
            'url' => 'http://webhook-test.rebll.to/capture-webhook.php?event=new-subscription'
        ])->create();
        $result = (new \Rebill\SDK\Models\Webhook)->setAttributes([
            'event' => 'new-payment',
            'url' => 'http://webhook-test.rebll.to/capture-webhook.php?event=new-payment'
        ])->create();
        $result = (new \Rebill\SDK\Models\Webhook)->setAttributes([
            'event' => 'payment-change-status',
            'url' => 'http://webhook-test.rebll.to/capture-webhook.php?event=payment-change-status'
        ])->create();
        $result = (new \Rebill\SDK\Models\Webhook)->setAttributes([
            'event' => 'subscription-change-status',
            'url' => 'http://webhook-test.rebll.to/capture-webhook.php?event=subscription-change-status'
        ])->create();
        $result = (new \Rebill\SDK\Models\Webhook)->setAttributes([
            'event' => 'process-headsup',
            'url' => 'http://webhook-test.rebll.to/capture-webhook.php?event=process-headsup'
        ])->create();
        $webhooks     = \Rebill\SDK\Models\Webhook::all();
        if ($webhooks && is_array($webhooks) && count($webhooks) == 5) {
            foreach ($webhooks as $webhook) {
                $webhook->delete();
            }
            $webhooks     = \Rebill\SDK\Models\Webhook::all();
            if ($webhooks && is_array($webhooks) && count($webhooks) > 0) {
                $this->assertTrue(false, 'Fail last delete all');
                return;
            }
            $this->assertTrue(true);
        } else {
            $this->assertTrue(false, 'Fail webhook create');
        }
    }
}
