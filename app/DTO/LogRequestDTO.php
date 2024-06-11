<?php

namespace App\DTO;

class LogRequestDTO
{
    public $url;
    public $http_method;
    public $controller;
    public $controller_action;
    public $request_body;
    public $request_header;
    public $user_id;
    public $ip_user;
    public $user_agent;
    public $answer_status;
    public $answer_body;
    public $answer_header;

    public function __construct($data)
    {
        $this->fillData($data);
    }

    private function fillData($data) {
        
        foreach($data as $key => $field) {
            if (property_exists($this, $key)) {
                $this->$key = $field;
            }
        }
    }
}