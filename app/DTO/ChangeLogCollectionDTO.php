<?php

namespace App\DTO;

class ChangeLogCollectionDTO
{
    public $logs;

    public function __construct($logs)
    {
        $this->logs = $logs->map(function ($log) {
            return new ChangeLogDTO($log);
        });
    }
}