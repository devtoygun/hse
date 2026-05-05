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
        Schema::create('forms', function (Blueprint $table) {
            // Primary key for each form record.
            $table->id();
            // Short title shown in listings and selection screens.
            $table->string('form_title');
            // Detailed description for the form content and purpose.
            $table->text('form_detail')->nullable();
            // Free-form notes or annotations for internal usage.
            $table->text('annotations')->nullable();
            // Controls whether notification emails are enabled for the form.
            $table->boolean('email_sending')->default(false);
            // Stores the notification recipient address when emailing is enabled.
            $table->string('email_recipient_address')->nullable();
            // Marks the form as active or inactive.
            $table->boolean('status')->default(true);
            // Tracks which user created the form.
            $table->foreignId('created_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            // Keeps standard creation and update timestamps.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
