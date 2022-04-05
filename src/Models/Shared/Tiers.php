<?php namespace Rebill\SDK\Models\Shared;

/**
*  Tiers
*
*  @author Kijam
*/
class Tiers extends SharedEntity
{
    /**
     * Amount
     *
     * @var string
     */
    public $amount;

    /**
     * Up to
     *
     * @var string
     */
    public $upTo;

    /**
     * ID
     *
     * @var string
     */
    public $id;
    
    /**
     * Validate Model attributes.
     *
     * @return object Recursive Model
     */
    public function validate()
    {
        if (!is_numeric($this->amount)) {
            \Rebill\SDK\Rebill::log('Tiers: amount is invalid '.var_export($this->amount, true));
            throw new \Exception("Tiers: The attribute amount is invalid.");
        }
        if (isset($this->upTo) && !is_numeric($this->upTo)) {
            \Rebill\SDK\Rebill::log('Tiers: upTo is invalid '.var_export($this->decimalPlaces, true));
            throw new \Exception("Tiers: The attribute upTo is invalid.");
        }
        if (!is_string($this->id)) {
            \Rebill\SDK\Rebill::log('Tiers: id is invalid '.var_export($this->id, true));
            throw new \Exception("Tiers: The attribute id is invalid.");
        }
        return $this;
    }
}
