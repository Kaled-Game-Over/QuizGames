<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameModeResource;
use App\Models\GameMode;
use Illuminate\Http\Request;

class GameModeController extends Controller
{
    /**
     * Get a specific game mode with its content.
     */
    public function show(Request $request, GameMode $gameMode)
    {
        $gameMode->load('contents');

        return new GameModeResource($gameMode);
    }

    /**
     * Get game mode for a specific lesson.
     */
    public function getByLesson(Request $request, $lessonId)
    {
        $gameMode = GameMode::where('lesson_id', $lessonId)
                           ->where('is_active', true)
                           ->with('contents')
                           ->first();

        if (!$gameMode) {
            return response()->json(['message' => 'No game mode found for this lesson'], 404);
        }

        return new GameModeResource($gameMode);
    }
} 