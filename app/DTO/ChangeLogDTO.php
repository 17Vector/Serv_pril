<?php

namespace App\DTO;

class ChangeLogDTO
{
    public $table;
    public $table_id;
    public $user_id;
    public $old_value;
    public $new_value;

    public function __construct($log)
    {
        $this->table = $log->table;
        $this->table_id = $log->table_id;
        $this->user_id = $log->user_id;

        $this->old_value = $this->getChanges($log->old_value, $log->new_value);
        $this->new_value = $this->getChanges($log->new_value, $log->old_value);
    }

    public function getChanges($new, $old) {
        if ($old === null && is_array($new))
        {
            return $new;
        }
        elseif (is_array($new) && is_array($old))
        {
            return array_filter($new, function ($key) use ($new, $old) {

                return array_key_exists($key, $old) && $new[$key] !== $old[$key];

            }, ARRAY_FILTER_USE_KEY);
        }
        else {
            return null;
        }
    }
}