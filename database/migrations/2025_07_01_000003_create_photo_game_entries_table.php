<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photo_game_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_mode_instance_id')->constrained()->onDelete('cascade');
            $table->string('question'); // <-- new field
            $table->json('correct_images');
            $table->json('wrong_images');
            $table->integer('answer');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photo_game_entries');
    }
};
