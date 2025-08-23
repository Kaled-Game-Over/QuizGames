<?php

namespace Database\Seeders;

use App\Models\Child;
use App\Models\GameMode;
use App\Models\GameModeContent;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\LessonContent;
use App\Models\Map;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class QuizGameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test user
        $user = User::create([
            'name' => 'Test Parent',
            'email' => 'parent@test.com',
            'password' => Hash::make('password'),
        ]);

        // Assign parent role to the user
        $user->assignRole('parent');

        // Create children
        // Create grades first
        $kindergarten = Grade::create(['name' => 'Kindergarten']);
        $grade1 = Grade::create(['name' => '1st Grade']);
        $grade2 = Grade::create(['name' => '2nd Grade']);
        $grade3 = Grade::create(['name' => '3rd Grade']);

        Child::create(['user_id' => $user->id, 'name' => 'Ahmed KG', 'grade_id' => $kindergarten->id]);
        Child::create(['user_id' => $user->id, 'name' => 'Sara Grade 1', 'grade_id' => $grade1->id]);
        Child::create(['user_id' => $user->id, 'name' => 'Omar Grade 2', 'grade_id' => $grade2->id]);

        // Create maps
        $kgMap = Map::create([
            'name' => 'Kindergarten Adventure World',
            'description' => 'A colorful world for kindergarten students',
            'grade_id' => $kindergarten->id,
            'image_path' => 'maps/kg-world.jpg',
        ]);

        $grade1Map = Map::create([
            'name' => '1st Grade Learning Kingdom',
            'description' => 'An exciting kingdom for 1st Grade students',
            'grade_id' => $grade1->id,
            'image_path' => 'maps/grade1-kingdom.jpg',
        ]);

        $grade2Map = Map::create([
            'name' => '2nd Grade Science Universe',
            'description' => 'A vast universe for 2nd Grade students',
            'grade_id' => $grade2->id,
            'image_path' => 'maps/grade2-universe.jpg',
        ]);

        // Create stages first
        $kgStage = \App\Models\Stage::create([
            'map_id' => $kgMap->id,
            'name' => 'Kindergarten Stage 1',
            'order' => 1,
        ]);

        $grade1Stage = \App\Models\Stage::create([
            'map_id' => $grade1Map->id,
            'name' => '1st Grade Stage 1',
            'order' => 1,
        ]);

        $grade2Stage = \App\Models\Stage::create([
            'map_id' => $grade2Map->id,
            'name' => '2nd Grade Stage 1',
            'order' => 1,
        ]);

        // Create lessons for KG
        $kgLesson = Lesson::create([
            'stage_id' => $kgStage->id,
            'title' => 'Colors and Shapes',
            'description' => 'Learn about basic colors and shapes',
            'order' => 1,
        ]);

        LessonContent::create([
            'lesson_id' => $kgLesson->id,
            'type' => 'text',
            'content' => 'Let\'s learn about colors and shapes!',
            'order' => 1,
        ]);

        $kgGameMode = GameMode::create([
            'name' => 'Count the Photos Game',
            'description' => 'Match the colors with the correct objects',
            'type' => 'PHOTO',
            'game_config' => ['time_limit' => 60, 'points_per_match' => 10],
        ]);

        GameModeContent::create([
            'game_mode_id' => $kgGameMode->id,
            'type' => 'text',
            'content' => 'Match the colors!',
            'order' => 1,
        ]);

        // Create lessons for Grade 1
        $grade1Lesson = Lesson::create([
            'stage_id' => $grade1Stage->id,
            'title' => 'Addition Basics',
            'description' => 'Learn basic addition with numbers 1-20',
            'order' => 1,
        ]);

        LessonContent::create([
            'lesson_id' => $grade1Lesson->id,
            'type' => 'text',
            'content' => 'Addition is combining numbers together',
            'order' => 1,
        ]);

        $grade1GameMode = GameMode::create([
            'name' => 'Tetris game',
            'description' => 'Solve addition problems',
            'type' => 'MATH',
            'game_config' => ['time_limit' => 30, 'points_per_correct' => 20],
        ]);

        GameModeContent::create([
            'game_mode_id' => $grade1GameMode->id,
            'type' => 'text',
            'content' => 'What is 5 + 3?',
            'order' => 1,
        ]);

        // Create lessons for Grade 2
        $grade2Lesson = Lesson::create([
            'stage_id' => $grade2Stage->id,
            'title' => 'Multiplication Tables',
            'description' => 'Learn multiplication tables 2-5',
            'order' => 1,
        ]);

        LessonContent::create([
            'lesson_id' => $grade2Lesson->id,
            'type' => 'text',
            'content' => 'Multiplication is repeated addition',
            'order' => 1,
        ]);

        $grade2GameMode = GameMode::create([
            'name' => 'Quiz test',
            'description' => 'Practice multiplication tables',
            'type' => 'QUIZ',
            'game_config' => ['time_limit' => 40, 'points_per_correct' => 30],
        ]);

        GameModeContent::create([
            'game_mode_id' => $grade2GameMode->id,
            'type' => 'text',
            'content' => 'What is 3 x 4?',
            'order' => 1,
        ]);
    }
} 