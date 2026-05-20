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
        Schema::create('units', function (Blueprint $table) {

            // --------------------------------------------------
            // ID
            // --------------------------------------------------
            $table->id();

            // --------------------------------------------------
            // Facility Relation
            // --------------------------------------------------
            $table->foreignId('facility_id')
                ->constrained('facilities')
                ->cascadeOnDelete();

            // --------------------------------------------------
            // Unit Name
            // --------------------------------------------------
            $table->string('unit');

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
        Schema::dropIfExists('units');
    }
};