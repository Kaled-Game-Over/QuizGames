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
        Schema::create('game_mode_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_mode_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['text', 'image', 'audio', 'video']);
            $table->text('content');
            $table->string('file_path')->nullable();
            $table->json('metadata')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_mode_contents');
    }
}; 