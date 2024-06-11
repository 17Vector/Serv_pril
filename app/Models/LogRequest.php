<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogRequest extends Model
{
    use HasFactory;

    protected $table = 'logs_requests';

    protected $fillable = [
        'url',
        'http_method',
        'controller',
        'controller_action',
        'request_body',
        'request_header',
        'user_id',
        'ip_user',
        'user_agent',
        'answer_status',
        'answer_body',
        'answer_header',
        'date',
    ];

    protected $casts = [
        'request_body' => 'array',
        'request_headers' => 'array',
        'answer_body' => 'array',
        'answer_header' => 'array',
        'date' => 'datetime',
    ];
}
