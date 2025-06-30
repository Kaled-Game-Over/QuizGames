<?php

namespace Database\Seeders;

use App\Models\Child;
use App\Models\GameMode;
use App\Models\GameModeContent;
use App\Models\Lesson;
use App\Models\LessonContent;
use App\Models\Map;
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

        // Create children
        Child::create(['user_id' => $user->id, 'name' => 'Ahmed KG', 'grade_level' => 'KG']);
        Child::create(['user_id' => $user->id, 'name' => 'Sara Grade 1', 'grade_level' => 'Grade 1']);
        Child::create(['user_id' => $user->id, 'name' => 'Omar Grade 2', 'grade_level' => 'Grade 2']);

        // Create maps
        $kgMap = Map::create([
            'name' => 'KG Adventure World',
            'description' => 'A colorful world for kindergarten students',
            'grade_level' => 'KG',
            'image_path' => 'maps/kg-world.jpg',
        ]);

        $grade1Map = Map::create([
            'name' => 'Grade 1 Learning Kingdom',
            'description' => 'An exciting kingdom for Grade 1 students',
            'grade_level' => 'Grade 1',
            'image_path' => 'maps/grade1-kingdom.jpg',
        ]);

        $grade2Map = Map::create([
            'name' => 'Grade 2 Science Universe',
            'description' => 'A vast universe for Grade 2 students',
            'grade_level' => 'Grade 2',
            'image_path' => 'maps/grade2-universe.jpg',
        ]);

        // Create lessons for KG
        $kgLesson = Lesson::create([
            'map_id' => $kgMap->id,
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
            'lesson_id' => $kgLesson->id,
            'name' => 'Color Matching Game',
            'description' => 'Match the colors with the correct objects',
            'type' => 'matching',
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
            'map_id' => $grade1Map->id,
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
            'lesson_id' => $grade1Lesson->id,
            'name' => 'Addition Quiz',
            'description' => 'Solve addition problems',
            'type' => 'quiz',
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
            'map_id' => $grade2Map->id,
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
            'lesson_id' => $grade2Lesson->id,
            'name' => 'Multiplication Quiz',
            'description' => 'Practice multiplication tables',
            'type' => 'quiz',
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