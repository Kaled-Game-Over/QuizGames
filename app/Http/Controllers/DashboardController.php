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
use App\Models\QuizGameQuestion;
use App\Models\GameModeInstance;
use App\Models\PhotoGameEntry;
use App\Models\MathGameProblem;
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

    public function createGameModeInstance(Request $request): JsonResponse
    {
        $request->validate([
            'game_mode_id' => 'required|exists:game_modes,id',
            'stage_id' => 'required|exists:stages,id',
            'config' => 'nullable|array', // optional config as array
        ]);

        $instance = GameModeInstance::create([
            'game_mode_id' => $request->game_mode_id,
            'stage_id' => $request->stage_id,
            'config' => $request->config ? json_encode($request->config) : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Game mode instance created successfully',
            'data' => $instance
        ], 201);
    }


    /**
     * Create a new question for a game mode
     */
    public function createQuestion(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'game_mode_instance_id' => 'required|exists:game_mode_instances,id',
                'question' => 'required|string',
                'choices' => 'nullable|array', // we can accept an array and cast to json
                'correct_option' => 'required|integer|min:0',
            ]);

            $question = \App\Models\QuizGameQuestion::create([
                'game_mode_instance_id' => $request->game_mode_instance_id,
                'question' => $request->question,
                'choices' => $request->choices ? json_encode($request->choices) : null,
                'correct_option' => $request->correct_option,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Quiz question created successfully',
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
            $question = \App\Models\QuizGameQuestion::findOrFail($id);

            $request->validate([
                'question' => 'sometimes|required|string',
                'choices' => 'nullable|array', // accept array
                'correct_option' => 'sometimes|required|integer|min:0',
            ]);

            $data = $request->only(['question', 'correct_option']);
            if ($request->has('choices')) {
                $data['choices'] = json_encode($request->choices);
            }

            $question->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Quiz question updated successfully',
                'data' => $question
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating question: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createPhotoEntry(Request $request): JsonResponse
    {
        $request->validate([
            'game_mode_instance_id' => 'required|exists:game_mode_instances,id',
            'correct_images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'wrong_images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'answer' => 'required|integer',
        ]);

        $data = [
            'game_mode_instance_id' => $request->game_mode_instance_id,
            'answer' => $request->answer,
        ];

        // Upload correct images
        if ($request->hasFile('correct_images')) {
            $correctImages = [];
            foreach ($request->file('correct_images') as $file) {
                $path = $file->store('photos/correct', 'public'); // stores in storage/app/public/photos/correct
                $correctImages[] = $path;
            }
            $data['correct_images'] = $correctImages;
        }

        // Upload wrong images
        if ($request->hasFile('wrong_images')) {
            $wrongImages = [];
            foreach ($request->file('wrong_images') as $file) {
                $path = $file->store('photos/wrong', 'public');
                $wrongImages[] = $path;
            }
            $data['wrong_images'] = $wrongImages;
        }

        $entry = PhotoGameEntry::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Photo game entry created successfully',
            'data' => $entry
        ], 201);
    }


    public function updatePhotoEntry(Request $request, $id): JsonResponse
    {
        $entry = PhotoGameEntry::findOrFail($id);

        $request->validate([
            'correct_images' => 'sometimes|array',
            'wrong_images' => 'sometimes|array',
            'answer' => 'sometimes|integer',
        ]);

        $data = $request->only(['answer']);
        if ($request->has('correct_images')) $data['correct_images'] = json_encode($request->correct_images);
        if ($request->has('wrong_images')) $data['wrong_images'] = json_encode($request->wrong_images);

        $entry->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Photo game entry updated successfully',
            'data' => $entry
        ]);
    }

    public function createMathProblem(Request $request): JsonResponse
    {
        $request->validate([
            'game_mode_instance_id' => 'required|exists:game_mode_instances,id',
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        $problem = MathGameProblem::create([
            'game_mode_instance_id' => $request->game_mode_instance_id,
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Math problem created successfully',
            'data' => $problem
        ], 201);
    }

    public function updateMathProblem(Request $request, $id): JsonResponse
    {
        $problem = MathGameProblem::findOrFail($id);

        $request->validate([
            'question' => 'sometimes|required|string',
            'answer' => 'sometimes|required|string',
        ]);

        $problem->update($request->only(['question', 'answer']));

        return response()->json([
            'success' => true,
            'message' => 'Math problem updated successfully',
            'data' => $problem
        ]);
    }
    /**
     * Get all grades
     */
    public function getGrades(): JsonResponse
    {
        try {
            $grades = Grade::all();

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
     * Get all maps
     */
    public function getMaps(): JsonResponse
    {
        try {
            $maps = Map::with('grade')->get();

            return response()->json([
                'success' => true,
                'data' => $maps
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching maps: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all stages
     */
    public function getStages(): JsonResponse
    {
        try {
            $stages = Stage::with('map')->get();

            return response()->json([
                'success' => true,
                'data' => $stages
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching stages: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all lessons
     */
    public function getLessons(): JsonResponse
    {
        try {
            $lessons = Lesson::with('stage')->get();

            return response()->json([
                'success' => true,
                'data' => $lessons
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching lessons: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all game modes
     */
    public function getGameModes(): JsonResponse
    {
        try {
            $gameModes = GameMode::with('lesson')->get();

            return response()->json([
                'success' => true,
                'data' => $gameModes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching game modes: ' . $e->getMessage()
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