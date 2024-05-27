<?php

namespace App\DTO;

class Role_DTO
{
    public $name;
    public $description;
    public $encryption;
    
    public function __construct($name, $description, $encryption)
    {
        $this->name = $name;
        $this->description = $description;
        $this->encryption = $encryption;
    }
}