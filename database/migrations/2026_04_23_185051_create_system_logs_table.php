<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('system_logs', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $blueprint->foreignId('target_user_id')->nullable()->constrained('users')->onDelete('cascade');
            $blueprint->string('action'); // Örn: 'password_reset'
            $blueprint->text('description'); // Örn: 'Kullanıcının şifresi sıfırlandı ve oturumları kapatıldı.'
            $blueprint->string('ip_address')->nullable();
            $blueprint->string('user_agent')->nullable();
            $blueprint->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('system_logs');
    }
};