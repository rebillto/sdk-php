<?php namespace Rebill\SDK\Models\Shared;

/**
*  OrganizationAddress
*
*  @author Kijam
*/
class OrganizationAddress extends SharedEntity
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
        foreach(get_object_vars($this) as $k => $v) {
            if (empty($v)) {
                \Rebill\SDK\Rebill::log('OrganizationAddress: '.$k.' is empty');
                throw new \Exception('The attribute '.$k.' is required.');
            }
            if (!is_string($v)) {
                \Rebill\SDK\Rebill::log('OrganizationAddress: '.$k.' not is string: '.var_export($v, true));
                throw new \Exception('The attribute '.$k.' not is string.');
            }
        }
        return $this;
    }
}
