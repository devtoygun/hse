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
        Schema::create('form_archive_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_archive_id')->constrained('form_archives')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('form_question_id')->constrained('form_questions')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('answer', 10);
            $table->timestamps();

            $table->index(['form_archive_id', 'form_question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_archive_answers');
    }
};
