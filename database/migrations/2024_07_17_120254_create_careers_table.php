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
        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->longText('name')->nullable();
            $table->longText('gender')->nullable();
            $table->longText('date_of_birth')->nullable();
            $table->longText('state')->nullable();
            $table->longText('location')->nullable();
            $table->longText('yearOfExperience')->nullable();
            $table->longText('levelOfExperience')->nullable();
            $table->longText('role')->nullable();
            $table->longText('courseOfStudy')->nullable();
            $table->longText('graduatingGrade')->nullable();
            $table->longText('yearOfGraduation')->nullable();
            $table->longText('cv')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('careers');
    }
};
