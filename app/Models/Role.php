<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Traits\ChangeLoggable;

class Role extends Model
{
    use HasFactory, SoftDeletes;
    use ChangeLoggable;

    protected $fillable = [
        'name',
        'description',
        'encryption',
        
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->encryption = Str::uuid();
            $model->created_by = Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        static::deleting(function ($model) {
            $model->deleted_by = Auth::id();
            $model->save();
        });

        static::restoring(function ($model) {
            $model->deleted_by = null;
        });
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_and_permissions');
    }
}
