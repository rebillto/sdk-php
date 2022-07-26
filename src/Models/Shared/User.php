<?php namespace Rebill\SDK\Models\Shared;

/**
*  User
*
*  @author Kijam
*/
class User extends SharedEntity
{
    /**
     * E-Mail
     *
     * @var string
     */
    public $email;

    /**
     * Password
     *
     * @var string
     */
    public $password;
    /**
     * Validate Model attributes.
     *
     * @return object Recursive Model
     */
    public function validate()
    {
        if (empty($this->email) || !\is_string($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            \Rebill\SDK\Rebill::log('User: email not is string: '.var_export($v, true));
            throw new \Exception('The attribute email not is string.');
        }
        if (empty($this->password) || !\is_string($this->password)) {
            \Rebill\SDK\Rebill::log('User: email not is string: '.var_export($v, true));
            throw new \Exception('The attribute email not is string.');
        }
        return $this;
    }
}
