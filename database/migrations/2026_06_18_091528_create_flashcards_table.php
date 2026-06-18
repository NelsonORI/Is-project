<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('flashcards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('document_id');
            $table->text('question');
            $table->text('answer');
            $table->float('confidence_score')->nullable();
            $table->integer('card_order');
            $table->timestamps();

            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('flashcards');
    }
};