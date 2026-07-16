<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormQuestion extends Model
{
    use HasFactory;

    /**
     * Attributes that can be mass assigned safely.
     *
     * @var list<string>
     */
    protected $fillable = [
        'form_id',
        'question_title',
        'question_order',
        'question_text',
        'approval_required',
        'send_nofitication',
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
            'question_order' => 'integer',
            'approval_required' => 'boolean',
            'send_nofitication' => 'boolean',
            'status' => 'boolean',
        ];
    }

    /**
     * Gets the parent form that owns the question.
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id', 'id');
    }

    public function archiveAnswers(): HasMany
    {
        return $this->hasMany(FormArchiveAnswer::class, 'form_question_id');
    }
}
