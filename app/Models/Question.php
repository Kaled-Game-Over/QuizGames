<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_mode_id',
        'question_text',
        'question_type',
        'options',
        'correct_answer',
        'points',
        'difficulty'
    ];

    protected $casts = [
        'options' => 'array'
    ];

    public function gameMode()
    {
        return $this->belongsTo(GameMode::class);
    }
} 