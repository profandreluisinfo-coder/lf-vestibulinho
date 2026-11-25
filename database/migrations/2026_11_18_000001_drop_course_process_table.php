<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('course_process');
    }

    public function down(): void
    {
        Schema::create('course_process', function (Blueprint $table) {
            $table->id();

            $table->foreignId('process_id')
                ->constrained('processes')
                ->cascadeOnDelete();

            $table->foreignId('course_id')
                ->constrained('courses')
                ->cascadeOnDelete();

            $table->unsignedInteger('vacancies');

            $table->timestamps();

            $table->unique(['process_id', 'course_id']);
        });
    }
};
