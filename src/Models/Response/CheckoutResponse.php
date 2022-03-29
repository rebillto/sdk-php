<?php namespace Rebill\SDK\Models\Response;

/**
*  CheckoutResponse class
*
*  @author Kijam
*/
class CheckoutResponse extends \Rebill\SDK\RebillModel
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
        'paidBags',
        'cartId',
        'buyer',
        'id'
    ];
    public function format()
    {
        if (isset($this->paidBags) && is_array($this->paidBags)) {
            foreach ($this->paidBags as $k => $v) {
                if (!is_object($v)) {
                    $this->paidBags[$k] = (new \Rebill\SDK\Models\Shared\PaidBag)->setAttributes($v);
                }
            }
        }
        return $this;
    }
}
