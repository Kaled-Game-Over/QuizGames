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
        // Add the new column
        Schema::table('photo_game_entries', function (Blueprint $table) {
            $table->string('question')->nullable()->after('game_mode_instance_id');
        });

        // Fill existing rows with a default value
        DB::table('photo_game_entries')->update([
            'question' => 'Default Question'
        ]);

        // Optional: make column NOT NULL after filling existing rows
        Schema::table('photo_game_entries', function (Blueprint $table) {
            $table->string('question')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('photo_game_entries', function (Blueprint $table) {
        $table->dropColumn('question');
    });
    }
};
