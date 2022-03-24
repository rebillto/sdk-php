<?php namespace Rebill\SDK\Models;

/**
*  Card class
*
*  @author Kijam
*/
class CardResponse extends \Rebill\SDK\RebillModel
{
    /**
     * Class of Model
     *
     * @var string
     */
    protected $class = self::class;
    /**
     * Attribute List
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'email',
        'enabled',
        'profiles',
        'cards'
    ];
}