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
        Schema::table('programs', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['school_id']);
            $table->dropForeign(['combination_id']);

            // Drop the columns
            $table->dropColumn(['school_id', 'combination_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('combination_id')->constrained()->onDelete('cascade');
        });
    }
};
