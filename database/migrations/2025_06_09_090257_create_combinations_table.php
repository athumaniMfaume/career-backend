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
        // Combinations table
        Schema::create('combinations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('shortname')->unique(); // <- Add shortname column
            $table->timestamps();
        });

        // Pivot table for combination-subject many-to-many
        Schema::create('combination_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('combination_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combination_subject'); // <- Drop pivot table first
        Schema::dropIfExists('combinations');
    }
};
