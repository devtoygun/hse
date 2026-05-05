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
        Schema::create('sub_forms', function (Blueprint $table) {
            // Primary key added so child questions can reference sub forms safely.
            $table->id();
            // Parent form that owns this sub form.
            $table->foreignId('form_id')->constrained('forms')->cascadeOnUpdate()->cascadeOnDelete();
            // Title shown for the sub form section.
            $table->string('form_title');
            // Marks the sub form as active or inactive.
            $table->boolean('status')->default(true);
            // Keeps standard creation and update timestamps for traceability.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_forms');
    }
};
