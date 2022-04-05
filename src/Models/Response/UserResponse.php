<?php namespace Rebill\SDK\Models\Response;

/**
*  UserResponse class
*
*  @author Kijam
*/
class UserResponse extends \Rebill\SDK\RebillModel
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
