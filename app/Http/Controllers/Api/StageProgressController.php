<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StageProgress;
use App\Models\Child;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StageProgressController extends Controller
{
    /**
     * Get all stage progress for a child
     */
    public function index(Request $request, Child $child): JsonResponse
    {
        // Ensure the child belongs to the authenticated user
        if ($child->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $progress = StageProgress::where('child_id', $child->id)
            ->with(['stage'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $progress,
            'child_name' => $child->name,
        ]);
    }

    /**
     * Get stage progress for a specific stage
     */
    public function show(Request $request, Child $child, Stage $stage): JsonResponse
    {
        // Ensure the child belongs to the authenticated user
        if ($child->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $progress = StageProgress::where('child_id', $child->id)
            ->where('stage_id', $stage->id)
            ->first();

        if (!$progress) {
            return response()->json([
                'success' => false,
                'message' => 'No progress found for this stage'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $progress->load('stage'),
        ]);
    }

    /**
     * Store or update stage progress (when child completes a stage)
     */
    public function store(Request $request, Child $child): JsonResponse
    {
        // Ensure the child belongs to the authenticated user
        if ($child->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'stage_id' => 'required|exists:stages,id',
            'total_score' => 'required|integer|min:0',
            'stars' => 'required|integer|min:0|max:3',
            'is_completed' => 'sometimes|boolean',
        ]);

        // Check if progress already exists for this stage
        $existingProgress = StageProgress::where('child_id', $child->id)
            ->where('stage_id', $request->stage_id)
            ->first();

        if ($existingProgress) {
            // Update existing progress if new score is higher
            if ($request->total_score > $existingProgress->total_score) {
                $existingProgress->update([
                    'total_score' => $request->total_score,
                    'stars' => $request->stars,
                    'is_completed' => $request->is_completed ?? true,
                    'completed_at' => now(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Stage progress updated successfully',
                'data' => $existingProgress->load('stage'),
            ]);
        }

        // Create new progress
        $progress = StageProgress::create([
            'child_id' => $child->id,
            'stage_id' => $request->stage_id,
            'total_score' => $request->total_score,
            'stars' => $request->stars,
            'is_completed' => $request->is_completed ?? true,
            'completed_at' => now(),
        ]);

        // Update child's current stage to the next stage
        if ($request->is_completed ?? true) {
            $this->updateChildCurrentStage($child, $request->stage_id);
        }

        return response()->json([
            'success' => true,
            'message' => 'Stage progress created successfully',
            'data' => $progress->load('stage'),
        ], 201);
    }

    /**
     * Update child's current stage to the next available stage
     */
    private function updateChildCurrentStage(Child $child, int $completedStageId): void
    {
        // Get the map for this child's grade
        $map = \App\Models\Map::where('grade_id', $child->grade_id)->first();
        
        if (!$map) {
            return;
        }

        // Get all stages in order
        $stages = \App\Models\Stage::where('map_id', $map->id)
            ->orderBy('order', 'asc')
            ->get();

        // Find the next stage after the completed one
        $nextStage = null;
        $foundCompleted = false;

        foreach ($stages as $stage) {
            if ($foundCompleted) {
                $nextStage = $stage;
                break;
            }
            
            if ($stage->id === $completedStageId) {
                $foundCompleted = true;
            }
        }

        // Update child's current stage
        $child->update(['current_stage_id' => $nextStage ? $nextStage->id : null]);
    }

    /**
     * Get child's total score across all stages
     */
    public function getTotalScore(Request $request, Child $child): JsonResponse
    {
        // Ensure the child belongs to the authenticated user
        if ($child->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $totalScore = StageProgress::where('child_id', $child->id)
            ->sum('total_score');

        $totalStars = StageProgress::where('child_id', $child->id)
            ->sum('stars');

        $completedStages = StageProgress::where('child_id', $child->id)
            ->where('is_completed', true)
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'child_name' => $child->name,
                'total_score' => $totalScore,
                'total_stars' => $totalStars,
                'completed_stages' => $completedStages,
            ],
        ]);
    }

    /**
     * Get leaderboard for a specific stage (for teachers)
     */
    public function getStageLeaderboard(Request $request, Stage $stage): JsonResponse
    {
        // Check if user is a teacher
        if ($request->user()->role !== 'teacher') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $leaderboard = StageProgress::where('stage_id', $stage->id)
            ->with(['child:id,name'])
            ->orderBy('total_score', 'desc')
            ->orderBy('stars', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'stage_name' => $stage->name,
                'leaderboard' => $leaderboard,
            ],
        ]);
    }
}
