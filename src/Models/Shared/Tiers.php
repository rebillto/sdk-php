<?php namespace Rebill\SDK\Models\Shared;

/**
*  Frequecy
*
*  @author Kijam
*/
class Tiers extends SharedEntity
{
    public $amount;
    public $upTo;
    public $id;
    public function validate()
    {
        if (!is_numeric($this->amount)) {
            \Rebill\SDK\Rebill::log('Tiers: amount is invalid '.var_export($this->amount, true));
            throw new \Exception("Tiers: The attribute amount is invalid.");
        }
        if (!is_numeric($this->upTo)) {
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
