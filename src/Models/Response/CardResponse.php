<?php namespace Rebill\SDK\Models\Response;

/**
*  CardResponse class
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
        'cardholder',
        'personalIdType',
        'personalIdNumber',
        'expirationMonth',
        'expirationYear',
        'cardNumber',
        'securityCodeLocation',
        'securityCode'
    ];
}
