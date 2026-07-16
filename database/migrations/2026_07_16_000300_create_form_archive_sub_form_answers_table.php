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
        if (Schema::hasTable('form_archive_sub_form_answers')) {
            return;
        }

        Schema::create('form_archive_sub_form_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_archive_id')->constrained('form_archives')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('sub_form_question_id')->constrained('sub_form_questions')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('answer', 10);
            $table->timestamps();

            $table->index(['form_archive_id', 'sub_form_question_id'], 'fasfa_archive_question_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_archive_sub_form_answers');
    }
};
