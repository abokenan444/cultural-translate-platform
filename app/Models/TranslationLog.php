<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TranslationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'user_id',
        'source_lang',
        'target_lang',
        'word_count',
        'model',
        'has_error',
    ];

    protected $casts = [
        'has_error' => 'boolean',
        'word_count' => 'integer',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
