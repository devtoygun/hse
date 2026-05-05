<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubForm extends Model
{
    use HasFactory;

    /**
     * Attributes that can be mass assigned safely.
     *
     * @var list<string>
     */
    protected $fillable = [
        'form_id',
        'form_title',
        'status',
    ];

    /**
     * Attribute cast definitions for consistent data access.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    /**
     * Gets the parent form that owns the sub form.
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id', 'id');
    }

    /**
     * Gets the questions that belong to the sub form.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(SubFormQuestion::class, 'subform_id');
    }
}
