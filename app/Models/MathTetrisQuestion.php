<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MathTetrisQuestion extends Model
{
    protected $fillable = [
        'game_mode_id',
        'question',
        'correct_answer',
        'points',
        'difficulty',
        'operation_type',
    ];

    public function gameMode(): BelongsTo
    {
        return $this->belongsTo(GameMode::class);
    }
}
