<?php namespace Rebill\SDK\Models\Shared;

/**
*  Address
*
*  @author Kijam
*/
class Address extends SharedEntity
{
    public $street;
    public $number;
    public $floor;
    public $apt;
    public $city;
    public $state;
    public $zipCode;
    public $country;
    public $description;

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
