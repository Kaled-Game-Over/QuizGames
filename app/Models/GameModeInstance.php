<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameModeInstance extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_mode_id',
        'stage_id',
        'config',
    ];

    protected $casts = [
        'config' => 'array',
    ];

    /**
     * The game mode this instance belongs to.
     */
    public function gameMode()
    {
        return $this->belongsTo(GameMode::class);
    }

    public function photoGameEntries()
    {
        return $this->hasMany(PhotoGameEntry::class);
    }

    public function quizGameQuestions()
    {
        return $this->hasMany(QuizGameQuestion::class);
    }

    public function mathGameProblems()
    {
        return $this->hasMany(MathGameProblem::class);
    }
    /**
     * The stage this instance belongs to.
     */
    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    public function isPhoto() { return $this->gameMode->type === 'PHOTO'; }
    public function isQuiz()  { return $this->gameMode->type === 'QUIZ'; }
    public function isMath()  { return $this->gameMode->type === 'MATH'; }
}