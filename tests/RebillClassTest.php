<?php

namespace Rebill\SDK\Tests;

use PHPUnit\Framework\TestCase;

/**
*  Corresponding Class to test Rebill class
*
*  @author Kijam
*/
class RebillClassTest extends TestCase
{
    /**
     * Just check if the Rebill has no syntax error
    *
    */
    public function isObject()
    {
        $var = new \Rebill\SDK\Rebill;
        $this->assertTrue(is_object($var));
        unset($var);
    }
}
