<?php namespace Rebill\SDK\Models\Shared;

/**
*  Frequecy
*
*  @author Kijam
*/
class Installment extends SharedEntity
{
    /**
     * ID for this installment in Gateway
     *
     * @var string
     */
    public $id;

    /**
     * Quantity
     *
     * @var int
     */
    public $quantity;

    /**
     * Individual Price
     *
     * @var float
     */
    public $individualPrice;

    /**
     * Total Price
     *
     * @var float
     */
    public $totalPrice;
    
    /**
     * Total CFT
     *
     * @var string|null
     */
    public $cft;
    
    /**
     * Total TEA
     *
     * @var string|null
     */
    public $tea;
    
    /**
     * Validate Model attributes.
     *
     * @return object Recursive Model
     */
    public function validate()
    {
        if (!is_numeric($this->quantity)) {
            \Rebill\SDK\Rebill::log('Installment: quantity is invalid '.var_export($this->quantity, true));
            throw new \Exception('The attribute quantity is invalid.');
        }
        return $this;
    }
}
