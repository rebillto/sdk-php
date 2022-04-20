<?php namespace Rebill\SDK\Models\Response;

/**
*  CheckoutBuyerResponse class
*
*  @author Kijam
*/
class CheckoutBuyerResponse extends \Rebill\SDK\RebillModel
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
        'customer',
        'card'
    ];

    /**
     * Check format of Attributes
     *
     * @return object Recursive Model
     */
    protected function format()
    {
        if (isset($this->customer) && !is_object($this->customer)) {
            $this->customer = (new \Rebill\SDK\Models\Shared\Profile)->setAttributes($this->customer);
        }
        if (isset($this->card) && !is_object($this->card)) {
            $this->card = (new \Rebill\SDK\Models\Response\CardResponse)->setAttributes($this->card);
        }
        return $this;
    }

}