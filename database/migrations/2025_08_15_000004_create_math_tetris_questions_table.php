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
        Schema::create('math_tetris_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_mode_id')->constrained('game_modes')->onDelete('cascade');
            $table->text('question'); // Math problem like "5 + 3 = ?"
            $table->string('correct_answer');
            $table->unsignedInteger('points')->default(20);
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy');
            $table->enum('operation_type', ['addition', 'subtraction', 'multiplication', 'division'])->default('addition');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('math_tetris_questions');
    }
};
