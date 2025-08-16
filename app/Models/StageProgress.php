<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StageProgress extends Model
{
    protected $table = 'stage_progress';

    protected $fillable = [
        'child_id',
        'stage_id',
        'total_score',
        'stars',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }
}
