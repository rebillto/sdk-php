<?php namespace Rebill\SDK\Models\Shared;

/**
*  CheckoutPayment
*
*  @author Kijam
*/
class CheckoutPayment extends SharedEntity
{
    /**
     * Price ID
     *
     * @var string
     */
    public $id;

    /**
     * currency
     *
     * @var string
     */
    public $currency;

    /**
     * Quantity
     *
     * @var string
     */
    public $quantity;

    /**
     * Amount
     *
     * @var string
     */
    public $amount;

    /**
     * metadata
     *
     * @var array<string,string>
     */
    public $metadata;

    /**
     * Status
     *
     * @var string
     */
    public $status;

    /**
     * Gateway
     *
     * @var \Rebill\SDK\Models\Response\GenericIdResponse
     */
    public $gateway;

    /**
     * Check format of Attributes
     *
     * @return object Recursive Model
     */
    protected function format()
    {
        if ($this->gateway && !is_object($this->gateway)) {
            $this->gateway = (new \Rebill\SDK\Models\Response\GenericIdResponse)->setAttributes($this->gateway);
        }
    }
}
