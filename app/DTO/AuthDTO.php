<?php

namespace App\DTO;

class AuthDTO
{
    public $username;
    public $password;
    
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
}