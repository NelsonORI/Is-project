<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('flashcard_metadata', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('flashcard_id');
            $table->string('unit_code');
            $table->string('lecturer');
            $table->string('exam_type');
            $table->string('semester');
            $table->string('academic_year');
            $table->timestamps();

            $table->foreign('flashcard_id')->references('id')->on('flashcards')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('flashcard_metadata');
    }
};