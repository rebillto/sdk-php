<?php namespace Rebill\SDK\Models\Shared;

/**
*  User
*
*  @author Kijam
*/
class User extends SharedEntity
{
    public $email;
    public $password;

    public function validate()
    {
        return $this;
    }
}
