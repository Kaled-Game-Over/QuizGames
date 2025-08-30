<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChildResource;
use App\Http\Resources\MapResource;
use App\Models\Child;
use App\Models\Map;
use Illuminate\Http\Request;

class ChildController extends Controller
{
    /**
     * Get all children for the authenticated user.
     */
    public function index(Request $request)
    {
        $children = $request->user()->children;
        
        return ChildResource::collection($children);
    }

    /**
     * Add a new child.
     */
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'grade_level' => 'required|in:Kindergarten,1st Grade,2nd Grade,3rd Grade',
    ]);

    // Find the grade ID based on the grade level name
    $grade = \App\Models\Grade::where('name', $request->grade_level)->first();
    
    if (!$grade) {
        return response()->json(['message' => 'Invalid grade level'], 422);
    }

    // Create child with default current_stage_id = 1
    $child = $request->user()->children()->create([
        'name' => $request->name,
        'grade_id' => $grade->id,
        'current_stage_id' => 1,
    ]);

    // Create 3 progress rows for stages 1, 2, 3
    for ($i = 1; $i <= 3; $i++) {
        \App\Models\ChildProgress::create([
            'child_id' => $child->id,
            'stage_id' => $i,
            'stars' => 0,
            'points' => 0,
        ]);
    }

    return new ChildResource($child);
}


    /**
     * Get a specific child.
     */
    public function show(Request $request, Child $child)
    {
        // Ensure the child belongs to the authenticated user
        if ($child->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return new ChildResource($child);
    }

    /**
     * Update a child.
     */
    public function update(Request $request, Child $child)
    {
        // Ensure the child belongs to the authenticated user
        if ($child->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'grade_level' => 'sometimes|required|in:Kindergarten,1st Grade,2nd Grade,3rd Grade',
        ]);

        $updateData = ['name' => $request->name];
        
        if ($request->has('grade_level')) {
            // Find the grade ID based on the grade level name
            $grade = \App\Models\Grade::where('name', $request->grade_level)->first();
            
            if (!$grade) {
                return response()->json(['message' => 'Invalid grade level'], 422);
            }
            
            $updateData['grade_id'] = $grade->id;
        }

        $child->update($updateData);

        return new ChildResource($child);
    }

    /**
     * Delete a child.
     */
    public function destroy(Request $request, Child $child)
    {
        // Ensure the child belongs to the authenticated user
        if ($child->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $child->delete();

        return response()->json(['message' => 'Child deleted successfully']);
    }

    /**
     * Get the map for a specific child.
     */
   public function getMap(Request $request, Child $child)
{
    // Ensure the child belongs to the authenticated user
    if ($child->user_id !== $request->user()->id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Load the map for the child's grade
    $map = Map::where('grade_id', $child->grade_id)
              ->with(['stages' => function($query) {
                  $query->orderBy('order', 'asc');
              }, 'grade']) // load grade relation
              ->first();

    if (!$map) {
        return response()->json(['message' => 'No map found for this grade'], 404);
    }

    // Get child's completed stages
    $completedStageIds = \App\Models\StageProgress::where('child_id', $child->id)
        ->pluck('stage_id')
        ->toArray();

    // Add completion info to each stage
    $map->stages->each(function($stage) use ($completedStageIds) {
        $stage->is_completed = in_array($stage->id, $completedStageIds);
    });

    // Load child's grade relation
    $child->load('grade');

    return response()->json([
        'success' => true,
        'data' => [
            'map' => [
                'id' => $map->id,
                'name' => $map->name,
                'description' => $map->description,
                'grade' => $map->grade?->name,
                'image_path' => $map->image_path,
            ],
            'stages' => $map->stages->map(function($stage) {
                return [
                    'id' => $stage->id,
                    'name' => $stage->name,
                    'order' => $stage->order,
                    'is_completed' => $stage->is_completed ?? false,
                ];
            }),
        ]
    ]);
}

    /**
     * Get the current accessible stage for a child.
     * Returns the stage stored in current_stage_id.
     */
    public function getCurrentStage(Request $request, Child $child)
    {
        // Ensure the child belongs to the authenticated user
        if ($child->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $map = Map::where('grade_id', $child->grade_id)
                  ->with(['stages' => function($query) {
                      $query->orderBy('order', 'asc');
                  }])
                  ->first();

        if (!$map) {
            return response()->json(['message' => 'No map found for this grade level'], 404);
        }

        // Get child's completed stages
        $completedStages = \App\Models\StageProgress::where('child_id', $child->id)
            ->where('is_completed', true)
            ->pluck('stage_id')
            ->toArray();

        $completedCount = count($completedStages);
        $totalStages = $map->stages->count();

        // If child has no current stage, set it to the first stage
        if (!$child->current_stage_id && $totalStages > 0) {
            $firstStage = $map->stages->first();
            $child->update(['current_stage_id' => $firstStage->id]);
            $child->refresh();
        }

        // Get current stage details
        $currentStage = $child->currentStage;
        $allCompleted = $completedCount === $totalStages;

        return response()->json([
            'success' => true,
            'data' => [
                'current_stage' => $currentStage ? [
                    'id' => $currentStage->id,
                    'name' => $currentStage->name,
                    'order' => $currentStage->order,
                    'is_completed' => in_array($currentStage->id, $completedStages),
                    'is_accessible' => true,
                ] : null,
                'next_stage' => null, // No longer needed since current_stage is the accessible one
                'all_completed' => $allCompleted,
                'total_stages' => $totalStages,
                'completed_stages' => $completedCount,
                'map' => [
                    'id' => $map->id,
                    'name' => $map->name,
                    'grade' => $map->grade->name,
                ],
                'child' => [
                    'id' => $child->id,
                    'name' => $child->name,
                    'grade_level' => $child->grade->name,
                    'current_stage_id' => $child->current_stage_id,
                ]
            ]
        ]);
    }

     /**
 * Update stars for a specific stage of a child.
 */
    /**
 * Update stars for a specific stage of a child.
 */
    public function updateProgress(Request $request, Child $child)
    {
        // Ensure the child belongs to the authenticated user
        if ($child->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'stage_id' => 'required|integer|exists:stages,id',
            'stars' => 'required|integer|min:0|max:3',
        ]);

        $stageId = $request->stage_id;

        // Find or create progress row for this stage
        $progress = \App\Models\ChildProgress::firstOrCreate(
            ['child_id' => $child->id, 'stage_id' => $stageId],
            ['stars' => 0, 'points' => 0]
        );

        // Update stars (keep the best score)
        $progress->stars = max($progress->stars, $request->stars);
        $progress->save();

        // If stars > 0 → update child's current_stage_id to the next stage IF it exists
        if ($progress->stars > 0) {
            $map = \App\Models\Map::where('grade_id', $child->grade_id)
                ->with(['stages' => function ($q) {
                    $q->orderBy('order', 'asc');
                }])
                ->first();

            if ($map) {
                $stages = $map->stages->values();
                $currentIndex = $stages->search(fn($s) => $s->id === $stageId);

                // If there's a next stage → update current_stage_id
                if ($currentIndex !== false && $currentIndex + 1 < $stages->count()) {
                    $nextStage = $stages[$currentIndex + 1];
                    $child->current_stage_id = $nextStage->id;
                    $child->save();
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Progress updated successfully',
            'data' => [
                'child_id' => $child->id,
                'updated_stage_id' => $stageId,
                'current_stage_id' => $child->current_stage_id,
                'stage_progress' => $progress,
            ]
        ]);

    } 
}