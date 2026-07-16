<?php

namespace App\Models;

use App\Services\FormDigitalSignatureService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormArchive extends Model
{
    use HasFactory;

    /**
     * Attributes that can be mass assigned safely.
     *
     * @var list<string>
     */
    protected $fillable = [
        'form_id',
        'user_id',
        'digital_signature',
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
            'status' => 'integer',
        ];
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(FormArchiveAnswer::class, 'form_archive_id');
    }

    public function subFormAnswers(): HasMany
    {
        return $this->hasMany(FormArchiveSubFormAnswer::class, 'form_archive_id');
    }

    public function decodeDigitalSignature(): ?array
    {
        if (empty($this->digital_signature)) {
            return null;
        }

        return app(FormDigitalSignatureService::class)->decode($this->digital_signature);
    }
}
