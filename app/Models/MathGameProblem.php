<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MathGameProblem extends Model
{
    protected $fillable = [
        'game_mode_instance_id',
        'question',
        'answer',
    ];

    public function gameModeInstance(): BelongsTo
    {
        return $this->belongsTo(GameModeInstance::class);
    }
}
