<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('math_game_problems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_mode_instance_id')->constrained()->onDelete('cascade');
            $table->string('question');
            $table->string('answer');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('math_game_problems');
    }
}; 