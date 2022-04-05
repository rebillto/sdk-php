<?php namespace Rebill\SDK\Models\Shared;

/**
*  Role
*
*  @author Kijam
*/
class Role extends SharedEntity
{
    /**
     * Name
     *
     * @var string
     */
    public $name;

    /**
     * Permissions
     *
     * @var string
     */
    public $permissions;

    /**
     * Organization
     *
     * @var string
     */
    public $organization;
}
