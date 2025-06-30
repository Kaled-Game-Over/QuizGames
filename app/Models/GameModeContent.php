<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameModeContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_mode_id',
        'type',
        'content',
        'file_path',
        'metadata',
        'order',
    ];

    protected $casts = [
        'type' => 'string',
        'metadata' => 'array',
    ];

    /**
     * Get the game mode that owns the content.
     */
    public function gameMode(): BelongsTo
    {
        return $this->belongsTo(GameMode::class);
    }
} 