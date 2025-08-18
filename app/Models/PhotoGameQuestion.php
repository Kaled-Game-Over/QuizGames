<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhotoGameQuestion extends Model
{
    protected $fillable = [
        'game_mode_id',
        'question',
        'images',
        'correct_answer',
        'points',
        'difficulty',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function gameMode(): BelongsTo
    {
        return $this->belongsTo(GameMode::class);
    }
}
