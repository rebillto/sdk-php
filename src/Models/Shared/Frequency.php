<?php namespace Rebill\SDK\Models\Shared;

/**
*  Frequecy
*
*  @author Kijam
*/
class Frequency extends SharedEntity
{
    /**
     * Type: 'days', 'months' or 'years'
     *
     * @var string
     */
    public $type;

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
        if (!in_array($this->type, ['days', 'months', 'years'], true)) {
            \Rebill\SDK\Rebill::log('Frequecy: type is invalid '.var_export($this->type, true));
            throw new \Exception('The attribute type is invalid.');
        }
        if (!is_numeric($this->quantity)) {
            \Rebill\SDK\Rebill::log('Frequecy: quantity is invalid '.var_export($this->type, true));
            throw new \Exception('The attribute quantity is invalid.');
        }
        return $this;
    }
}
