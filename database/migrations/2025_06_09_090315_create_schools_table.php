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
    Schema::create('schools', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique();
        $table->enum('type', ['college', 'university']);
        $table->enum('education_level', ['o_level', 'a_level', 'both']); // school level offered
        $table->timestamps();
    });

    Schema::create('combination_school', function (Blueprint $table) {
        $table->id();
        $table->foreignId('combination_id')->constrained()->onDelete('cascade');
        $table->foreignId('school_id')->constrained()->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};

