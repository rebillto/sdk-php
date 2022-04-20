<?php namespace Rebill\SDK\Models\Shared;

/**
*  PaidBag
*
*  @author Kijam
*/
class PaidBag extends SharedEntity
{
    /**
     * Array of Prices
     *
     * @var array<\Rebill\SDK\Models\CheckoutPrice>
     */
    public $prices;

    /**
     * Payment Object
     *
     * @var \Rebill\SDK\Models\Payment
     */
    public $payment;

    /**
     * Subscriptions List
     *
     * @var array
     */
    public $schedules;

    /**
     * Check format of Attributes
     *
     * @return object Recursive Model
     */
    protected function format()
    {
        if ($this->payment && !is_object($this->payment)) {
            $this->payment = (new \Rebill\SDK\Models\Payment)->setAttributes($this->payment);
        }
        if ($this->prices) {
            foreach ($this->prices as $k => $v) {
                if (!is_object($v)) {
                    $this->prices[$k] = (new \Rebill\SDK\Models\Shared\CheckoutPrice)->setAttributes($v);
                }
            }
        }
        return $this;
    }
}
