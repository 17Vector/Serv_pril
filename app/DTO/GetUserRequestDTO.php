<?php

namespace App\DTO;

class GetUserRequestDTO
{
    public int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }
}