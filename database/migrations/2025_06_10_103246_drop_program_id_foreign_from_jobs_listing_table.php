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
        Schema::table('jobs_listing', function (Blueprint $table) {
            // Drop foreign key constraint and the program_id column
            $table->dropForeign(['program_id']);
            $table->dropColumn('program_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs_listing', function (Blueprint $table) {
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
        });
    }
};
