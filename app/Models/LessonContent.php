<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'type',
        'content',
        'file_path',
        'order',
    ];

    protected $casts = [
        'type' => 'string',
    ];

    /**
     * Get the lesson that owns the content.
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
} 