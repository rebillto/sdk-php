<?php namespace Rebill\SDK\Models\Response;

/**
*  GenericIdResponse class
*
*  @author Kijam
*/
class GatewayResponse extends \Rebill\SDK\RebillModel
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
        'gatewayId'
    ];
}
