<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'map_id',
        'title',
        'description',
        'image_path',
        'video_path',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the map that owns the lesson.
     */
    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class);
    }

    /**
     * Get the lesson contents for this lesson.
     */
    public function contents(): HasMany
    {
        return $this->hasMany(LessonContent::class)->orderBy('order');
    }

    /**
     * Get the game mode for this lesson.
     */
    public function gameMode(): HasOne
    {
        return $this->hasOne(GameMode::class);
    }

    /**
     * Get the subject that owns the lesson.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the progress records for the lesson.
     */
    public function progress()
    {
        return $this->hasMany(ChildProgress::class);
    }
} 