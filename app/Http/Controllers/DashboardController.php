<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\Child;
use App\Models\Grade;
use App\Models\Map;
use App\Models\Stage;
use App\Models\Lesson;
use App\Models\GameMode;
use App\Models\Question;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get all players with their children and performance data
     */
    public function getAllPlayers(): JsonResponse
    {
        try {
            $players = User::with(['children' => function($query) {
                $query->with(['grades', 'performance']);
            }])->where('role', 'parent')->get();

            $formattedPlayers = $players->map(function($player) {
                return [
                    'id' => $player->id,
                    'name' => $player->name,
                    'email' => $player->email,
                    'children' => $player->children->map(function($child) {
                        return [
                            'id' => $child->id,
                            'name' => $child->name,
                            'age' => $child->age,
                            'grade_level' => $child->grade_level,
                            'performance' => $child->performance ?? [],
                            'grades' => $child->grades ?? []
                        ];
                    })
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedPlayers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching players data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get children by grade level with their performance
     */
    public function getChildrenByGrade(Request $request): JsonResponse
    {
        try {
            $gradeLevel = $request->get('grade_level');
            
            $children = Child::with(['user', 'performance', 'grades'])
                ->when($gradeLevel, function($query, $gradeLevel) {
                    return $query->where('grade_level', $gradeLevel);
                })
                ->get();

            $groupedChildren = $children->groupBy('grade_level');

            return response()->json([
                'success' => true,
                'data' => $groupedChildren
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching children by grade: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new grade/curriculum
     */
    public function createGrade(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'level' => 'required|integer|min:1'
            ]);

            $grade = Grade::create([
                'name' => $request->name,
                'description' => $request->description,
                'level' => $request->level
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Grade created successfully',
                'data' => $grade
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating grade: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new map for a grade
     */
    public function createMap(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'grade_id' => 'required|exists:grades,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image_path' => 'nullable|string'
            ]);

            $map = Map::create([
                'grade_id' => $request->grade_id,
                'name' => $request->name,
                'description' => $request->description,
                'image_path' => $request->image_path
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Map created successfully',
                'data' => $map
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating map: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new stage in a map
     */
    public function createStage(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'map_id' => 'required|exists:maps,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'order' => 'required|integer|min:1',
                'is_unlocked' => 'boolean'
            ]);

            $stage = Stage::create([
                'map_id' => $request->map_id,
                'name' => $request->name,
                'description' => $request->description,
                'order' => $request->order,
                'is_unlocked' => $request->is_unlocked ?? false
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Stage created successfully',
                'data' => $stage
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating stage: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new lesson in a stage
     */
    public function createLesson(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'stage_id' => 'required|exists:stages,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'content' => 'nullable|string',
                'order' => 'required|integer|min:1'
            ]);

            $lesson = Lesson::create([
                'stage_id' => $request->stage_id,
                'name' => $request->name,
                'description' => $request->description,
                'content' => $request->content,
                'order' => $request->order
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lesson created successfully',
                'data' => $lesson
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating lesson: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new game mode for a stage
     */
    public function createGameMode(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'stage_id' => 'required|exists:stages,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|string|max:255',
                'settings' => 'nullable|json'
            ]);

            $gameMode = GameMode::create([
                'stage_id' => $request->stage_id,
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type,
                'settings' => $request->settings
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Game mode created successfully',
                'data' => $gameMode
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating game mode: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new question for a game mode
     */
    public function createQuestion(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'game_mode_id' => 'required|exists:game_modes,id',
                'question_text' => 'required|string',
                'question_type' => 'required|string|max:255',
                'options' => 'nullable|json',
                'correct_answer' => 'required|string',
                'points' => 'required|integer|min:1',
                'difficulty' => 'required|string|in:easy,medium,hard'
            ]);

            $question = Question::create([
                'game_mode_id' => $request->game_mode_id,
                'question_text' => $request->question_text,
                'question_type' => $request->question_type,
                'options' => $request->options,
                'correct_answer' => $request->correct_answer,
                'points' => $request->points,
                'difficulty' => $request->difficulty
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Question created successfully',
                'data' => $question
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating question: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a lesson
     */
    public function updateLesson(Request $request, $id): JsonResponse
    {
        try {
            $lesson = Lesson::findOrFail($id);
            
            $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'content' => 'nullable|string',
                'order' => 'sometimes|required|integer|min:1'
            ]);

            $lesson->update($request->only(['name', 'description', 'content', 'order']));

            return response()->json([
                'success' => true,
                'message' => 'Lesson updated successfully',
                'data' => $lesson
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating lesson: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a game mode
     */
    public function updateGameMode(Request $request, $id): JsonResponse
    {
        try {
            $gameMode = GameMode::findOrFail($id);
            
            $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'sometimes|required|string|max:255',
                'settings' => 'nullable|json'
            ]);

            $gameMode->update($request->only(['name', 'description', 'type', 'settings']));

            return response()->json([
                'success' => true,
                'message' => 'Game mode updated successfully',
                'data' => $gameMode
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating game mode: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a question
     */
    public function updateQuestion(Request $request, $id): JsonResponse
    {
        try {
            $question = Question::findOrFail($id);
            
            $request->validate([
                'question_text' => 'sometimes|required|string',
                'question_type' => 'sometimes|required|string|max:255',
                'options' => 'nullable|json',
                'correct_answer' => 'sometimes|required|string',
                'points' => 'sometimes|required|integer|min:1',
                'difficulty' => 'sometimes|required|string|in:easy,medium,hard'
            ]);

            $question->update($request->only([
                'question_text', 'question_type', 'options', 
                'correct_answer', 'points', 'difficulty'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Question updated successfully',
                'data' => $question
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating question: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all grades with their maps and stages
     */
    public function getGradesWithMaps(): JsonResponse
    {
        try {
            $grades = Grade::with(['maps.stages.lessons', 'maps.stages.gameModes.questions'])->get();

            return response()->json([
                'success' => true,
                'data' => $grades
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching grades: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(): JsonResponse
    {
        try {
            $stats = [
                'total_players' => User::where('role', 'parent')->count(),
                'total_children' => Child::count(),
                'total_grades' => Grade::count(),
                'total_maps' => Map::count(),
                'total_stages' => Stage::count(),
                'total_lessons' => Lesson::count(),
                'total_game_modes' => GameMode::count(),
                'total_questions' => Question::count(),
                'children_by_grade' => Child::select('grade_level', DB::raw('count(*) as count'))
                    ->groupBy('grade_level')
                    ->get()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching dashboard stats: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the dashboard main page.
     */
    public function index(Request $request)
    {
        // For now, just return a simple message or view
        return response()->json(['message' => 'Dashboard is under construction.']);
    }
} 