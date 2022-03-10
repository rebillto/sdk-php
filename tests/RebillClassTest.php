<?php

namespace Rebill\SDK\Tests;

use PHPUnit\Framework\TestCase;

/**
*  Test Rebill class
*
*  @author Kijam
*/
class RebillClassTest extends TestCase
{
    static $test_log_callback = false;

    /**
     * Just check if the Rebill has no syntax error
     * @test
     */
    public function isObject()
    {
        $obj = \Rebill\SDK\Rebill::getInstance();
        $this->assertTrue(is_object($obj));
        unset($obj);
    }
    /**
     * Just check if the Rebill has no syntax error
     * @test
     */
    public function checkDebugCallback()
    {
        self::$test_log_callback = false;
        \Rebill\SDK\Rebill::getInstance()->isDebug = true;
        \Rebill\SDK\Rebill::getInstance()->setCallBackDebugLog(function ($data) {
            self::$test_log_callback = ($data == 'testOK');
        });
        \Rebill\SDK\Rebill::log('testOK');
        $this->assertTrue(self::$test_log_callback);
    }
    /**
     * Check setProp method
     * @test
     */
    public function checkSetProp()
    {
        $this->assertTrue(\Rebill\SDK\Rebill::getInstance()->setProp(['user' => 'test1'])->getUser() == 'test1');
    }
    /**
     * Check Magic method
     * @test
     */
    public function checkMagicMethod()
    {
        $this->assertTrue(\Rebill\SDK\Rebill::getInstance()->setUser('test2')->getUser() == 'test2');
    }
    /**
     * Check Sandbox URL method
     * @test
     */
    public function checkSandboxURL()
    {
        $this->assertTrue(
            \Rebill\SDK\Rebill::getInstance()->setSandbox(true)->getUrl() == \Rebill\SDK\Rebill::API_SANDBOX
        );
    }
    /**
     * Check Production URL method
     * @test
     */
    public function checkProdURL()
    {
        $this->assertTrue(
            \Rebill\SDK\Rebill::getInstance()->setSandbox(false)->getUrl() == \Rebill\SDK\Rebill::API_PROD
        );
    }
}
