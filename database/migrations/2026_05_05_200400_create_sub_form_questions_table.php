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
        Schema::create('sub_form_questions', function (Blueprint $table) {
            // Primary key for each sub form question.
            $table->id();
            // Parent sub form that owns this question.
            $table->foreignId('subform_id')->constrained('sub_forms')->cascadeOnUpdate()->cascadeOnDelete();
            // Short label used to identify the question.
            $table->string('question_title');
            // Determines the display order inside the sub form.
            $table->integer('question_order');
            // Stores the user-facing question content.
            $table->string('question_text');
            // Indicates whether approval is required for this item.
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
        Schema::dropIfExists('sub_form_questions');
    }
};
