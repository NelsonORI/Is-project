<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('class_rep_id');
            $table->string('original_filename');
            $table->string('storage_path');
            $table->enum('processing_status', ['processing', 'ocr_done', 'published'])->default('processing');
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamps();

            $table->foreign('class_rep_id')->references('id')->on('class_reps')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('documents');
    }
};