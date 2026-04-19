<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LastLogin extends Model
{
    use HasFactory;

    protected $table = 'last_logins';

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'device',   
        'platform', 
        'browser',
        'login_at',
        'ended_at'
    ];
}
