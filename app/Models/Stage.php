<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stage extends Model
{
    use HasFactory;

    protected $fillable = [
        'map_id',
        'name',
        'order',
    ];

    /**
     * Get the map that owns this stage.
     */
    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class);
    }

    /**
     * Get the lessons for this stage.
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }
} 