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
        'grade_id',
        'current_stage_id',
    ];

    /**
     * Get the user (parent) that owns the child.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the grade for this child.
     */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * Get the current stage for this child.
     */
    public function currentStage(): BelongsTo
    {
        return $this->belongsTo(Stage::class, 'current_stage_id');
    }

    /**
     * Get the map for this child's grade level.
     */
    public function map()
    {
        return $this->grade ? $this->grade->maps()->first() : null;
    }

    /**
     * Get the progress records for the child.
     */
    public function progress()
    {
        return $this->hasMany(ChildProgress::class);
    }
} 