<?php

namespace App\DTO;

class UsersAndRolesCollectionDTO
{
    public $user_id;
    public $roles;
    
    public function __construct($user)
    {
        $this -> user_id = $user -> id;
        $this -> roles = $user -> roles -> map(function ($user) {
            return [
                'role_id' => $user -> id,
                'name' => $user -> name,
                'description' => $user -> description,
            ];
        });
    }
}