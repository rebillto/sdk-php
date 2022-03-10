<?php namespace Rebill\SDK\Models\Response;

/**
*  MerchantSignupResponse class
*
*  @author Kijam
*/
class MerchantSignupResponse extends \Rebill\SDK\RebillModel
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
        'userId',
        'organizationAlias',
        'authToken',
        'organizationId'
    ];
}
