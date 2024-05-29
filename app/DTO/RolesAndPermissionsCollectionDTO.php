<?php

namespace App\DTO;

class RolesAndPermissionsCollectionDTO
{
    public $role_id;
    public $permissions;
    
    public function __construct($role)
    {
        $this -> role_id = $role -> id;
        $this -> permissions = $role -> permissions -> map(function ($role) {
            return [
                'permission_id' => $role -> id,
                'name' => $role -> name,
                'description' => $role -> description,
            ];
        });
    }
}