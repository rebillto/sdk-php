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
        'id',
        'bin',
        'last4',
        'expiration',
        'cardholder',
        'personalIdType',
        'personalIdNumber',
        'expirationMonth',
        'expirationYear',
        'cardNumber',
        'securityCodeLocation',
        'securityCode'
    ];
    
    /**
     * Check format of Attributes
     *
     * @return object Recursive Model
     */
    protected function format()
    {
        if (isset($this->expiration) && !is_object($this->expiration)) {
            $this->expiration = (new \Rebill\SDK\Models\Shared\Expiration)->setAttributes($this->expiration);
        }
        return $this;
    }
}
