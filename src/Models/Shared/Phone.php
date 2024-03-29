<?php namespace Rebill\SDK\Models\Shared;

/**
*  Phone
*
*  @author Kijam
*/
class Phone extends SharedEntity
{
    /**
     * Country Code
     *
     * @var string
     */
    public $countryCode;

    /**
     * Area Code
     *
     * @var string
     */
    public $areaCode;

    /**
     * Phone Number
     *
     * @var string
     */
    public $phoneNumber;

    /**
     * Validate Model attributes.
     *
     * @return object Recursive Model
     */
    public function validate()
    {
        foreach (get_object_vars($this) as $k => $v) {
            if (!empty($v) && !\is_string($v) && !\is_numeric($v)) {
                \Rebill\SDK\Rebill::log('Phone: '.$k.' not is string: '.var_export($v, true));
                throw new \Exception('The attribute '.$k.' not is string.');
            }
        }
        return $this;
    }
}
