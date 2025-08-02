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
            'grade_level' => 'required|in:KG,Grade 1,Grade 2,3rd Grade',
        ]);

        // Find the grade ID based on the grade level name
        $grade = \App\Models\Grade::where('name', $request->grade_level)->first();
        
        if (!$grade) {
            return response()->json(['message' => 'Invalid grade level'], 422);
        }

        $child = $request->user()->children()->create([
            'name' => $request->name,
            'grade_id' => $grade->id,
        ]);

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
            'grade_level' => 'sometimes|required|in:KG,Grade 1,Grade 2,3rd Grade',
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

        $map = Map::where('grade_level', $child->grade_level)
                  ->where('is_active', true)
                  ->with('activeLessons')
                  ->first();

        if (!$map) {
            return response()->json(['message' => 'No map found for this grade level'], 404);
        }

        return new MapResource($map);
    }
} 