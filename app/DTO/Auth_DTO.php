<?php

namespace App\DTO;

class Auth_DTO
{
    public $username;
    public $password;
    
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
}