<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizGameQuestion extends Model
{
    protected $fillable = [
        'game_mode_instance_id',
        'question',
        'choices',
        'correct_option',
    ];

    protected $casts = [
        'choices' => 'array',
    ];

    public function gameModeInstance(): BelongsTo
    {
        return $this->belongsTo(GameModeInstance::class);
    }
}
