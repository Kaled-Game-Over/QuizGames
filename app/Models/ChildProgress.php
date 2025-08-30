<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChildProgress extends Model
{
    protected $table = 'child_progress';

    protected $fillable = [
        'child_id',
        'stage_id', 
        'stars',
        'points',
        'last_accessed',
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
