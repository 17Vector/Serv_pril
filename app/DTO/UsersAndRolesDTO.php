<?php

namespace App\DTO;

class UsersAndRolesDTO
{
    public $user_id;
    public $role_id;
    
    public function __construct($user_id, $role_id)
    {
        $this->user_id = $user_id;
        $this->role_id = $role_id;
    }
}