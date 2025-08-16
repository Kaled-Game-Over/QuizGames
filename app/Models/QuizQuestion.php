<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizQuestion extends Model
{
    protected $fillable = [
        'game_mode_id',
        'question',
        'options',
        'correct_answer',
        'points',
        'difficulty',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function gameMode(): BelongsTo
    {
        return $this->belongsTo(GameMode::class);
    }
}
