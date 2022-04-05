<?php namespace Rebill\SDK\Models\Shared;

/**
*  CardHolder
*
*  @author Kijam
*/
class CardHolder extends SharedEntity
{
    /**
     * Cardholder Name
     *
     * @var string
     */
    public $name;

    /**
     * Cardholder Identification
     *
     * @var \Rebill\SDK\Models\Shared\TaxId
     */
    public $identification;

    /**
     * Validate Model attributes.
     *
     * @return object Recursive Model
     */
    public function validate()
    {
        if (empty($this->name) || !\is_string($this->name)) {
            \Rebill\SDK\Rebill::log('CardHolder: name not is string: '.var_export($v, true));
            throw new \Exception('The attribute name not is string.');
        }
        if ($this->identification && !($this->identification instanceof \Rebill\SDK\Models\Shared\TaxId)) {
            \Rebill\SDK\Rebill::log('CardHolder: identification not is a TaxId instance: '.var_export($v, true));
            throw new \Exception('The attribute identification not is a TaxId instance.');
        }
        return $this;
    }

    /**
     * Check format of Attributes
     *
     * @return object Recursive Model
     */
    protected function format()
    {
        if ($this->identification && !is_object($this->identification)) {
            $this->identification = (new \Rebill\SDK\Models\Shared\TaxId)->setAttributes($this->identification);
        }
        return $this;
    }
}
