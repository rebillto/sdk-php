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
            \Rebill\SDK\Rebill::getInstance()->setSandbox(true)->getUrl() == 'https://api-staging.rebill.to/v1'
        );
    }
    /**
     * Check Production URL method
     * @test
     */
    public function checkProdURL()
    {
        $this->assertTrue(\Rebill\SDK\Rebill::getInstance()->setSandbox(false)->getUrl() == 'https://api.rebill.to/v1');
    }
}