<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_mode_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_mode_id')->constrained()->onDelete('cascade');
            $table->foreignId('stage_id')->constrained()->onDelete('cascade');
            $table->json('config')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_mode_instances');
    }
}; 