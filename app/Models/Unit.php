<?php

// ======================================================
// app/Models/Unit.php
// ======================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Unit extends Model
{
    // --------------------------------------------------
    // Fillable
    // --------------------------------------------------
    protected $fillable = [
        'facility_id',
        'unit',
    ];

    // --------------------------------------------------
    // Relations
    // --------------------------------------------------
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }
}