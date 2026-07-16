<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormArchiveSubFormAnswer extends Model
{
    use HasFactory;

    /**
     * Attributes that can be mass assigned safely.
     *
     * @var list<string>
     */
    protected $fillable = [
        'form_archive_id',
        'sub_form_question_id',
        'answer',
    ];

    public function archive(): BelongsTo
    {
        return $this->belongsTo(FormArchive::class, 'form_archive_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(SubFormQuestion::class, 'sub_form_question_id');
    }
}
