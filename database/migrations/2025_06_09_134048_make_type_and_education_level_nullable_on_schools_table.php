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
        Schema::table('schools', function (Blueprint $table) {
            $table->enum('type', ['college', 'university'])->nullable()->change();
            $table->enum('education_level', ['o_level', 'a_level', 'both'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
              $table->enum('type', ['college', 'university'])->nullable(false)->change();
            $table->enum('education_level', ['o_level', 'a_level', 'both'])->nullable(false)->change();
        });
    }
};
