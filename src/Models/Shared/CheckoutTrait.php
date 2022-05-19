<?php namespace Rebill\SDK\Models\Shared;

/**
*  CheckoutPrice
*
*  @author Kijam
*/
class CheckoutTrait extends SharedEntity
{
    /**
     * ID
     *
     * @var string
     */
    public $id;

    /**
     * Buyer
     *
     * @var mixed
     */
    public $buyer;

    /**
     * List of PaidBag
     *
     * @var array<\Rebill\SDK\Models\Shared\PaidBag>
     */
    public $paidBags;

    /**
     * Cart ID
     *
     * @var string
     */
    public $cartId;

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
        return $this;
    }
}
