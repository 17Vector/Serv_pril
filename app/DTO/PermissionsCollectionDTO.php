<?php

namespace App\DTO;

class PermissionsCollectionDTO
{
    public $permissions;
    
    public function __construct($permissions)
    {
        $this->permissions = $permissions;
    }
}