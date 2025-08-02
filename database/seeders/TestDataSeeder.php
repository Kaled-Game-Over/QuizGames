<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Map;
use App\Models\Stage;
use App\Models\Lesson;
use App\Models\GameMode;
use App\Models\Subject;
use App\Models\Grade;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or find a test grade
        $grade = Grade::firstOrCreate([
            'name' => '3rd Grade',
        ]);

        // Create or find a test map
        $map = Map::firstOrCreate([
            'name' => 'Test Map',
        ], [
            'description' => 'A test map for API testing',
            'grade_id' => $grade->id,
            'is_active' => true,
        ]);

        // Create or find a test stage
        $stage = Stage::firstOrCreate([
            'map_id' => $map->id,
            'name' => 'Test Stage',
        ], [
            'order' => 1,
        ]);

        // Create or find a test subject
        $subject = Subject::firstOrCreate([
            'name' => 'Mathematics',
        ]);

        // Create or find a test lesson
        $lesson = Lesson::firstOrCreate([
            'stage_id' => $stage->id,
            'title' => 'Test Lesson',
        ], [
            'subject_id' => $subject->id,
            'description' => 'A test lesson for API testing',
            'order' => 1,
            'is_active' => true,
        ]);

        // Create or find a test game mode
        $gameMode = GameMode::firstOrCreate([
            'lesson_id' => $lesson->id,
            'name' => 'Quiz Game',
        ], [
            'description' => 'A test quiz game',
            'type' => 'quiz',
            'is_active' => true,
        ]);

        $this->command->info('Test data seeded successfully!');
        $this->command->info('Lesson ID: ' . $lesson->id);
        $this->command->info('Game Mode ID: ' . $gameMode->id);
    }
} 