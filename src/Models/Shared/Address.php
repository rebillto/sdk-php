<?php namespace Rebill\SDK\Models\Shared;

/**
*  Address
*
*  @author Kijam
*/
class Address extends SharedEntity
{
    /**
     * Street
     *
     * @var string
     */
    public $street;

    /**
     * Number
     *
     * @var string
     */
    public $number;

    /**
     * Floor
     *
     * @var string
     */
    public $floor;

    /**
     * Apartment
     *
     * @var string
     */
    public $apt;

    /**
     * City
     *
     * @var string
     */
    public $city;

    /**
     * State or Province
     *
     * @var string
     */
    public $state;

    /**
     * Postal Code
     *
     * @var string
     */
    public $zipCode;

    /**
     * Country
     *
     * @var string
     */
    public $country;

    /**
     * Description Address
     *
     * @var string
     */
    public $description;

    /**
     * Validate Model attributes.
     *
     * @return object Recursive Model
     */
    public function validate()
    {
        foreach (get_object_vars($this) as $k => $v) {
            if (in_array($k, ['floor', 'apt', 'number']) && empty($v)) {
                continue;
            }
            if (empty($v)) {
                \Rebill\SDK\Rebill::log('Address: '.$k.' is empty');
                throw new \Exception('The attribute '.$k.' is required.');
            }
            if (!is_string($v)) {
                \Rebill\SDK\Rebill::log('Address: '.$k.' not is string: '.var_export($v, true));
                throw new \Exception('The attribute '.$k.' not is string.');
            }
        }
        return $this;
    }
}
