<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Child extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'grade_level',
    ];

    protected $casts = [
        'grade_level' => 'string',
    ];

    /**
     * Get the user (parent) that owns the child.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the map for this child's grade level.
     */
    public function map()
    {
        return Map::where('grade_level', $this->grade_level)->first();
    }
} 