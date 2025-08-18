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
        'grade_id',      // <-- make sure you have this column
        'image_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Map belongs to a grade
    public function grade()
    {
        return $this->belongsTo(\App\Models\Grade::class);
    }

    // Map has many stages
    public function stages(): HasMany
    {
        return $this->hasMany(Stage::class)->orderBy('order', 'asc');
    }

    // Map has many lessons
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function activeLessons(): HasMany
    {
        return $this->lessons()->where('is_active', true);
    }
}
