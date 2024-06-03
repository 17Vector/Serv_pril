<?php

namespace App\Traits;

use App\Models\ChangeLog;
use Illuminate\Support\Facades\Auth;

trait ChangeLoggable
{
    public static function bootChangeLoggable()
    {
        static::created(function ($model) {
            $model->logChange('created');
        });

        static::updated(function ($model) {
            $model->logChange('updated');
        });

        static::deleted(function ($model) {
            $model->logChange('deleted');
        });
    }

    public function logChange($action)
    {
        $user = Auth::user();

        $log = new ChangeLog();
        $log->table = get_class($this);
        $log->table_id = $this->id;
        $log->user_id = $user ? $user->id : null;

        if ($event === 'created')
        {
            $log->old_value = $this->getOriginal();
            $log->new_value = $this->getAttributes();
        }

        else if ($event === 'updated')
        {
            $log->old_value = null;
            $log->new_value = $this->getAttributes();
        }

        else if ($event === 'deleted')
        {
            $log->old_value = $this->getAttributes();
            $log->new_value = null;
        }

        $log->save();
    }
}