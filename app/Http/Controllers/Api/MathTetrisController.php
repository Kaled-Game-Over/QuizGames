<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MathTetrisQuestion;
use App\Models\GameMode;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MathTetrisController extends Controller
{
    /**
     * Get math tetris questions with optional limit
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'game_mode_id' => 'required|exists:game_modes,id',
            'limit' => 'sometimes|integer|min:1|max:50',
            'difficulty' => 'sometimes|in:easy,medium,hard',
            'operation_type' => 'sometimes|in:addition,subtraction,multiplication,division',
        ]);

        $query = MathTetrisQuestion::where('game_mode_id', $request->game_mode_id);

        if ($request->has('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        if ($request->has('operation_type')) {
            $query->where('operation_type', $request->operation_type);
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
     * Store a new math tetris question
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'game_mode_id' => 'required|exists:game_modes,id',
            'question' => 'required|string|max:1000',
            'correct_answer' => 'required|string|max:255',
            'points' => 'sometimes|integer|min:1|max:100',
            'difficulty' => 'sometimes|in:easy,medium,hard',
            'operation_type' => 'sometimes|in:addition,subtraction,multiplication,division',
        ]);

        $question = MathTetrisQuestion::create([
            'game_mode_id' => $request->game_mode_id,
            'question' => $request->question,
            'correct_answer' => $request->correct_answer,
            'points' => $request->points ?? 20,
            'difficulty' => $request->difficulty ?? 'easy',
            'operation_type' => $request->operation_type ?? 'addition',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Math tetris question created successfully',
            'data' => $question
        ], 201);
    }

    /**
     * Get a specific math tetris question
     */
    public function show(MathTetrisQuestion $question): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $question
        ]);
    }

    /**
     * Update a math tetris question
     */
    public function update(Request $request, MathTetrisQuestion $question): JsonResponse
    {
        $request->validate([
            'question' => 'sometimes|required|string|max:1000',
            'correct_answer' => 'sometimes|required|string|max:255',
            'points' => 'sometimes|integer|min:1|max:100',
            'difficulty' => 'sometimes|in:easy,medium,hard',
            'operation_type' => 'sometimes|in:addition,subtraction,multiplication,division',
        ]);

        $question->update($request->only([
            'question', 'correct_answer', 'points', 'difficulty', 'operation_type'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Math tetris question updated successfully',
            'data' => $question
        ]);
    }

    /**
     * Delete a math tetris question
     */
    public function destroy(MathTetrisQuestion $question): JsonResponse
    {
        $question->delete();

        return response()->json([
            'success' => true,
            'message' => 'Math tetris question deleted successfully'
        ]);
    }
}
