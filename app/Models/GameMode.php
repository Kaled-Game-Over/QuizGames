<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameMode extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'game_config',
        'is_active',
    ];

    protected $casts = [
        'game_config' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the lesson that owns the game mode.
     */
    
    public function instances()
    {
        return $this->hasMany(GameModeInstance::class);
    }
    
    /**
     * Get the game mode contents.
     */
    public function contents(): HasMany
    {
        return $this->hasMany(GameModeContent::class)->orderBy('order');
    }
} 