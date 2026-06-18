<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('search_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->string('query_string');
            $table->json('filter_params')->nullable();
            $table->boolean('exact_match_found')->default(false);
            $table->timestamp('searched_at')->useCurrent();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('search_logs');
    }
};