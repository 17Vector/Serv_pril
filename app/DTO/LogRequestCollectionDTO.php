<?php

namespace App\DTO;

class LogRequestCollectionDTO
{
    public $logs;

    public function __construct($logs)
    {
        $this->logs = $logs->map(function ($log) {
            return [
                'url' => $log['url'],
                'controller' => $log['controller'],
                'controller_action' => $log['controller_action'],
                'answer_status' => $log['answer_status'],
                'date' => $log['date'],
            ];
        });
    }
}