<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResetCode extends Model
{
    protected $fillable = [
        'email',
        'code',
        'status',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'integer',
            'expires_at' => 'datetime',
        ];
    }
}

