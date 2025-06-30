<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_game_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_mode_instance_id')->constrained()->onDelete('cascade');
            $table->text('question');
            $table->json('choices');
            $table->integer('correct_option');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_game_questions');
    }
}; 