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
    Schema::table('child_progress', function (Blueprint $table) {
        $table->dropForeign(['lesson_id']);
        $table->dropColumn('lesson_id');

        $table->foreignId('stage_id')
              ->constrained('stages')
              ->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('child_progress', function (Blueprint $table) {
        $table->dropForeign(['stage_id']);
        $table->dropColumn('stage_id');

        $table->foreignId('lesson_id')
              ->constrained('lessons')
              ->onDelete('cascade');
    });
}
};
