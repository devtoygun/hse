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
        Schema::create('active_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('session_id')->unique();
            $table->text('user_agent')->nullable();
            $table->string('browser_name')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('operating_system')->nullable();
            $table->string('device_type')->nullable();
            $table->string('device_family')->nullable();
            $table->string('device_fingerprint', 64)->index();
            $table->string('ip_address', 45)->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('logged_in_at')->nullable()->index();
            $table->timestamp('last_seen_at')->nullable()->index();
            $table->timestamp('logged_out_at')->nullable();
            $table->unsignedBigInteger('active_duration_seconds')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('active_sessions');
    }
};
