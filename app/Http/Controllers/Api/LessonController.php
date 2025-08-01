<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonContent;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    /**
     * Display the specified lesson.
     */
    public function show(Lesson $lesson)
    {
        $lesson->load('contents');
        return response()->json([
            'lesson' => $lesson,
            'contents' => $lesson->contents
        ]);
    }

    /**
     * Get lessons by map ID.
     */
    public function getByMap($mapId)
    {
        $lessons = Lesson::where('map_id', $mapId)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
        
        return response()->json(['lessons' => $lessons]);
    }

    /**
     * Create a new lesson (teacher only).
     */
    public function store(Request $request)
    {
        if ($request->user()->role !== 'teacher') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'map_id' => 'required|exists:maps,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp',
            'video' => 'nullable|file|mimes:mp4,avi,mov,webm',
        ]);

        $data = $validated;

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('lessons/images', 'public');
        }
        if ($request->hasFile('video')) {
            $data['video_path'] = $request->file('video')->store('lessons/videos', 'public');
        }

        $lesson = Lesson::create($data);
        return response()->json(['message' => 'Lesson created', 'lesson' => $lesson], 201);
    }

    /**
     * Add a question/content to a lesson (teacher only).
     */
    public function addContent(Request $request, Lesson $lesson)
    {
        if ($request->user()->role !== 'teacher') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'type' => 'required|in:text,image,video',
            'content' => 'required|string',
            'file_path' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $content = $lesson->contents()->create($validated);
        return response()->json(['message' => 'Content added', 'content' => $content], 201);
    }
} 