<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PhotoGameQuestion;
use App\Models\GameMode;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PhotoGameController extends Controller
{
    /**
     * Get photo game questions with optional limit
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'game_mode_id' => 'required|exists:game_modes,id',
            'limit' => 'sometimes|integer|min:1|max:50',
            'difficulty' => 'sometimes|in:easy,medium,hard',
        ]);

        $query = PhotoGameQuestion::where('game_mode_id', $request->game_mode_id);

        if ($request->has('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        $limit = $request->get('limit', 10);
        $questions = $query->inRandomOrder()->limit($limit)->get();

        return response()->json([
            'success' => true,
            'data' => $questions,
            'count' => $questions->count(),
        ]);
    }

    /**
     * Store a new photo game question
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'game_mode_id' => 'required|exists:game_modes,id',
            'question' => 'required|string|max:1000',
            'images' => 'required|array|min:1',
            'images.*' => 'required|string|max:500', // Image paths/URLs
            'correct_answer' => 'required|string|max:255',
            'points' => 'sometimes|integer|min:1|max:100',
            'difficulty' => 'sometimes|in:easy,medium,hard',
        ]);

        $question = PhotoGameQuestion::create([
            'game_mode_id' => $request->game_mode_id,
            'question' => $request->question,
            'images' => $request->images,
            'correct_answer' => $request->correct_answer,
            'points' => $request->points ?? 15,
            'difficulty' => $request->difficulty ?? 'easy',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Photo game question created successfully',
            'data' => $question
        ], 201);
    }

    /**
     * Get a specific photo game question
     */
    public function show(PhotoGameQuestion $question): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $question
        ]);
    }

    /**
     * Update a photo game question
     */
    public function update(Request $request, PhotoGameQuestion $question): JsonResponse
    {
        $request->validate([
            'question' => 'sometimes|required|string|max:1000',
            'images' => 'sometimes|required|array|min:1',
            'images.*' => 'required|string|max:500',
            'correct_answer' => 'sometimes|required|string|max:255',
            'points' => 'sometimes|integer|min:1|max:100',
            'difficulty' => 'sometimes|in:easy,medium,hard',
        ]);

        $question->update($request->only([
            'question', 'images', 'correct_answer', 'points', 'difficulty'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Photo game question updated successfully',
            'data' => $question
        ]);
    }

    /**
     * Delete a photo game question
     */
    public function destroy(PhotoGameQuestion $question): JsonResponse
    {
        $question->delete();

        return response()->json([
            'success' => true,
            'message' => 'Photo game question deleted successfully'
        ]);
    }
}
