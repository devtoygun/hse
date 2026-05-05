<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    use HasFactory;

    /**
     * Attributes that can be mass assigned safely.
     *
     * @var list<string>
     */
    protected $fillable = [
        'form_title',
        'form_detail',
        'annotations',
        'email_sending',
        'email_recipient_address',
        'status',
        'created_by',
    ];

    /**
     * Attribute cast definitions for consistent data access.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_sending' => 'boolean',
            'status' => 'boolean',
        ];
    }

    /**
     * Gets the user record that created the form.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Gets the top-level questions that belong to the form.
     */
    public function formQuestions(): HasMany
    {
        return $this->hasMany(FormQuestion::class, 'form_id', 'id');
    }

    /**
     * Gets the sub forms that belong to the form.
     */
    public function subForms(): HasMany
    {
        return $this->hasMany(SubForm::class, 'form_id', 'id');
    }
}
