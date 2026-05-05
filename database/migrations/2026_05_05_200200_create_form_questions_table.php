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
        Schema::create('form_questions', function (Blueprint $table) {
            // Primary key for each question row.
            $table->id();
            // Parent form that owns this question.
            $table->foreignId('form_id')->constrained('forms')->cascadeOnUpdate()->cascadeOnDelete();
            // Short label used when identifying the question.
            $table->string('question_title');
            // Determines the display order inside the form.
            $table->integer('question_order');
            // Stores the actual question text shown to users.
            $table->string('question_text');
            // Indicates whether this question requires approval.
            $table->boolean('approval_required')->default(false);
            // Marks the question as active or inactive.
            $table->boolean('status')->default(true);
            // Keeps standard creation and update timestamps for auditing.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_questions');
    }
};
