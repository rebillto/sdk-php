<?php namespace Rebill\SDK\Models\Shared;

/**
*  OrganizationAddress
*
*  @author Kijam
*/
class User extends SharedEntity
{
    public $email;
    public $password;

    public function validate()
    {
        foreach(get_object_vars($this) as $k => $v) {
            if (empty($v)) {
                \Rebill\SDK\Rebill::log('User: '.$k.' is empty');
                throw new \Exception('User: The attribute '.$k.' is required.');
            }
            if (!is_string($v)) {
                \Rebill\SDK\Rebill::log('User: '.$k.' not is string: '.var_export($v, true));
                throw new \Exception('User: The attribute '.$k.' not is string.');
            }
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            \Rebill\SDK\Rebill::log('User: email is invalid '.var_export($this->email, true));
            throw new \Exception('User: The attribute email not is email.');
        }
        return $this;
    }
}
