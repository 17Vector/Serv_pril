<?php

namespace App\DTO;

class User_DTO
{
    private $username;
    private $email;
    private $password;
    private $birthday;
    
    public function __construct($username, $email, $password, $birthday)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->birthday = $birthday;
    }
}