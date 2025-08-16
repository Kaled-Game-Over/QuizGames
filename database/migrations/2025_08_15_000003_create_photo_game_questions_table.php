<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('photo_game_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_mode_id')->constrained('game_modes')->onDelete('cascade');
            $table->text('question');
            $table->json('images'); // Array of image paths
            $table->string('correct_answer');
            $table->unsignedInteger('points')->default(15);
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_game_questions');
    }
};
