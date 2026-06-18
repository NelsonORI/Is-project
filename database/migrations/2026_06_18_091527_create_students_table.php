<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password_hash');
            $table->enum('role', ['student', 'class_rep'])->default('student');
            $table->string('student_number')->unique();
            $table->string('school');
            $table->string('programme');
            $table->integer('year_of_study');
            $table->enum('status', ['pending', 'active', 'suspended'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('students');
    }
};