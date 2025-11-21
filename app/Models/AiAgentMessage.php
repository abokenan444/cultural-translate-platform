<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AiAgentMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role',
        'message',
        'response',
        'meta',
        'status',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
