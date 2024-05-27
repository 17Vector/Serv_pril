<?php

namespace App\DTO;

class DTO_Roles_Collection
{
    public $roles = [];
    
    public function __construct($roles)
    {
        $this->roles = $roles;
    }
}