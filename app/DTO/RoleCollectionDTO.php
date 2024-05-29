<?php

namespace App\DTO;

class RoleCollectionDTO
{
    public $roles;
    
    public function __construct($roles)
    {
        $this->roles = $roles;
    }
}