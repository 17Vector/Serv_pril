<?php

namespace App\DTO;

class RolesAndPermissionsDTO
{
    public $role_id;
    public $permission_id;
    
    public function __construct($role_id, $permission_id)
    {
        $this->role_id = $role_id;
        $this->permission_id = $permission_id;
    }
}