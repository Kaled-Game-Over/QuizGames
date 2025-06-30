<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Map extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'grade_level',
        'image_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the lessons for this map.
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    /**
     * Get active lessons for this map.
     */
    public function activeLessons(): HasMany
    {
        return $this->lessons()->where('is_active', true);
    }
} 