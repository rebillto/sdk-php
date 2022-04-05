<?php namespace Rebill\SDK\Models\Shared;

/**
*  TaxId
*
*  @author Kijam
*/
class TaxId extends SharedEntity
{
    /**
     * Type of Identification ID
     *
     * @var string
     */
    public $type;

    /**
     * Identification ID
     *
     * @var string
     */
    public $value;

    /**
     * Validate Model attributes.
     *
     * @return object Recursive Model
     */
    public function validate()
    {
        foreach (get_object_vars($this) as $k => $v) {
            if (!empty($v) && !\is_string($v) && !\is_numeric($v)) {
                \Rebill\SDK\Rebill::log('TaxId: '.$k.' not is string: '.var_export($v, true));
                throw new \Exception('The attribute '.$k.' not is string.');
            }
        }
        return $this;
    }
}
