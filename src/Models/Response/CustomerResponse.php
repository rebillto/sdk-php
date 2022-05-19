<?php namespace Rebill\SDK\Models\Response;

/**
*  CustomerResponse class
*
*  @author Kijam
*/
class CustomerResponse extends \Rebill\SDK\RebillModel
{
    /**
     * Class of Model
     *
     * @var string
     */
    protected $class = self::class;
    
    /**
     * Attribute List
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'firstName',
        'lastName',
        'cellPhone',
        'birthday',
        'taxIdType',
        'taxIdNumber',
        'personalIdType',
        'personalIdNumber',
        'address',
        'role',
        'user',
    ];

    /**
     * Check format of Attributes
     *
     * @return object Recursive Model
     */
    protected function format()
    {
        if (isset($this->address) && !is_object($this->address)) {
            $this->address = (new \Rebill\SDK\Models\Shared\Address)->setAttributes($this->address);
        }
        if (isset($this->role) && !is_object($this->role)) {
            $this->role = (new \Rebill\SDK\Models\Shared\Role)->setAttributes($this->role);
        }
        if (isset($this->user) && !is_object($this->user)) {
            $this->user = (new \Rebill\SDK\Models\Shared\User)->setAttributes($this->user);
        }
        return $this;
    }
}
