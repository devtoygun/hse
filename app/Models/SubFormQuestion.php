<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubFormQuestion extends Model
{
    use HasFactory;

    /**
     * Attributes that can be mass assigned safely.
     *
     * @var list<string>
     */
    protected $fillable = [
        'subform_id',
        'question_title',
        'question_order',
        'question_text',
        'approval_required',
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
            'status' => 'boolean',
        ];
    }

    /**
     * Gets the parent sub form that owns the question.
     */
    public function subForm(): BelongsTo
    {
        return $this->belongsTo(SubForm::class, 'subform_id');
    }

    public function archiveAnswers(): HasMany
    {
        return $this->hasMany(FormArchiveSubFormAnswer::class, 'sub_form_question_id');
    }
}
