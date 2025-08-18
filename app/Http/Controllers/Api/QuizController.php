<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuizQuestion;
use App\Models\GameMode;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class QuizController extends Controller
{
    /**
     * Get quiz questions with optional limit
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'game_mode_id' => 'required|exists:game_modes,id',
            'limit' => 'sometimes|integer|min:1|max:50',
            'difficulty' => 'sometimes|in:easy,medium,hard',
        ]);

        $query = QuizQuestion::where('game_mode_id', $request->game_mode_id);

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
     * Store a new quiz question
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'game_mode_id' => 'required|exists:game_modes,id',
            'question' => 'required|string|max:1000',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
            'correct_answer' => 'required|string|max:255',
            'points' => 'sometimes|integer|min:1|max:100',
            'difficulty' => 'sometimes|in:easy,medium,hard',
        ]);

        // Verify that correct_answer exists in options
        if (!in_array($request->correct_answer, $request->options)) {
            return response()->json([
                'success' => false,
                'message' => 'Correct answer must be one of the provided options'
            ], 422);
        }

        $question = QuizQuestion::create([
            'game_mode_id' => $request->game_mode_id,
            'question' => $request->question,
            'options' => $request->options,
            'correct_answer' => $request->correct_answer,
            'points' => $request->points ?? 10,
            'difficulty' => $request->difficulty ?? 'easy',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Quiz question created successfully',
            'data' => $question
        ], 201);
    }

    /**
     * Get a specific quiz question
     */
    public function show(QuizQuestion $question): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $question
        ]);
    }

    /**
     * Update a quiz question
     */
    public function update(Request $request, QuizQuestion $question): JsonResponse
    {
        $request->validate([
            'question' => 'sometimes|required|string|max:1000',
            'options' => 'sometimes|required|array|min:2',
            'options.*' => 'required|string|max:255',
            'correct_answer' => 'sometimes|required|string|max:255',
            'points' => 'sometimes|integer|min:1|max:100',
            'difficulty' => 'sometimes|in:easy,medium,hard',
        ]);

        if ($request->has('options') && $request->has('correct_answer')) {
            if (!in_array($request->correct_answer, $request->options)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Correct answer must be one of the provided options'
                ], 422);
            }
        }

        $question->update($request->only([
            'question', 'options', 'correct_answer', 'points', 'difficulty'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Quiz question updated successfully',
            'data' => $question
        ]);
    }

    /**
     * Delete a quiz question
     */
    public function destroy(QuizQuestion $question): JsonResponse
    {
        $question->delete();

        return response()->json([
            'success' => true,
            'message' => 'Quiz question deleted successfully'
        ]);
    }
}
