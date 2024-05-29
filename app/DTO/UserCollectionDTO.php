<?php

namespace App\DTO;

class UserCollectionDTO
{
    public $users;
    
    public function __construct($users)
    {
        $this->users = $users;
    }
}