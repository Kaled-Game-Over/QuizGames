<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhotoGameEntry extends Model
{
    protected $fillable = [
        'game_mode_instance_id',
        'correct_images',
        'wrong_images',
        'answer',
    ];

    protected $casts = [
        'correct_images' => 'array',
        'wrong_images'   => 'array',
    ];

    public function gameModeInstance(): BelongsTo
    {
        return $this->belongsTo(GameModeInstance::class);
    }
}
