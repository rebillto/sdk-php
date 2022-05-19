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
        'invoice',
        'pendingTransaction',
        'failedTransaction'
    ];

    /**
     * Check format of Attributes
     *
     * @return object Recursive Model
     */
    protected function format()
    {
        if (isset($this->invoice) && !is_object($this->invoice)) {
            $this->invoice = (new \Rebill\SDK\Models\Shared\CheckoutTrait)->setAttributes($this->invoice);
        }
        if (isset($this->pendingTransaction) && !is_object($this->pendingTransaction)) {
            $this->pendingTransaction = (new \Rebill\SDK\Models\Shared\CheckoutTrait)->setAttributes($this->pendingTransaction);
        }
        if (isset($this->failedTransaction) && !is_object($this->failedTransaction)) {
            $this->failedTransaction = (new \Rebill\SDK\Models\Shared\CheckoutTrait)->setAttributes($this->failedTransaction);
        }
        return $this;
    }
}
