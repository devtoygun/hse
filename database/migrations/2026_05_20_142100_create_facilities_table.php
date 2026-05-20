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
        Schema::create('facilities', function (Blueprint $table) {

            // --------------------------------------------------
            // ID
            // --------------------------------------------------
            $table->id();

            // --------------------------------------------------
            // Facility Name
            // --------------------------------------------------
            $table->string('facility');

            // --------------------------------------------------
            // Timestamps
            // --------------------------------------------------
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};