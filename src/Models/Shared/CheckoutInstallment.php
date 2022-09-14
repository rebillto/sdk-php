<?php namespace Rebill\SDK\Models\Shared;

/**
*  CheckoutPrice
*
*  @author Kijam
*/
class CheckoutInstallment extends SharedEntity
{
    /**
     * Installment Gateway ID
     *
     * @var string
     */
    public $id;

    /**
     * Quantity
     *
     * @var string
     */
    public $quantity;

    /**
     * Validate Model attributes.
     *
     * @return object Recursive Model
     */
    public function validate()
    {
        if (empty($this->id) || !is_string($this->id)) {
            \Rebill\SDK\Rebill::log('CheckoutInstallment: id is invalid '.var_export($this->id, true));
            throw new \Exception('The attribute id is invalid.');
        }
        if (!is_numeric($this->quantity) || $this->quantity < 1) {
            \Rebill\SDK\Rebill::log('CheckoutInstallment: quantity is invalid '.var_export($this->type, true));
            throw new \Exception('The attribute quantity is invalid.');
        }
        return $this;
    }
}
