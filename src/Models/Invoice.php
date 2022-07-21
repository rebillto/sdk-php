<?php namespace Rebill\SDK\Models;

/**
*  Invoice
*
*  @author Kijam
*/
class Invoice extends \Rebill\SDK\RebillModel
{
    /**
     * Endpoint of Model
     *
     * @var int
     */
    protected static $endpoint = '/receipts';
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
        'cartId',
        'organizationId',
        'paidBags',
        'buyer',
        'type',
        'createdAt',
        'id'
    ];

    /**
     * Check format of Attributes
     *
     * @return object Recursive Model
     */
    protected function format()
    {
        if (isset($this->paidBags) && is_array($this->paidBags)) {
            foreach ($this->paidBags as $k => $v) {
                if (!is_object($v)) {
                    $this->paidBags[$k] = (new \Rebill\SDK\Models\Shared\PaidBag)->setAttributes($v);
                }
            }
        }
        if (isset($this->buyer) && is_array($this->buyer)) {
            $this->buyer = (new \Rebill\SDK\Models\Response\CheckoutBuyerResponse)->setAttributes($this->buyer);
        }
        return $this;
    }
}