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
}
