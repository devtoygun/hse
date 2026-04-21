<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('active_sessions', 'browser_name')) {
            Schema::table('active_sessions', function (Blueprint $table) {
                $table->string('browser_name')->nullable()->after('user_agent');
            });
        }

        if (! Schema::hasColumn('active_sessions', 'browser_version')) {
            Schema::table('active_sessions', function (Blueprint $table) {
                $table->string('browser_version')->nullable()->after('browser_name');
            });
        }

        if (! Schema::hasColumn('active_sessions', 'operating_system')) {
            Schema::table('active_sessions', function (Blueprint $table) {
                $table->string('operating_system')->nullable()->after('browser_version');
            });
        }

        if (! Schema::hasColumn('active_sessions', 'device_type')) {
            Schema::table('active_sessions', function (Blueprint $table) {
                $table->string('device_type')->nullable()->after('operating_system');
            });
        }

        if (! Schema::hasColumn('active_sessions', 'device_family')) {
            Schema::table('active_sessions', function (Blueprint $table) {
                $table->string('device_family')->nullable()->after('device_type');
            });
        }

        if (! Schema::hasColumn('active_sessions', 'device_fingerprint')) {
            Schema::table('active_sessions', function (Blueprint $table) {
                $table->string('device_fingerprint', 64)->nullable()->after('device_family');
            });
        }

        if (! Schema::hasColumn('active_sessions', 'is_active')) {
            Schema::table('active_sessions', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('ip_address');
            });
        }

        if (! Schema::hasColumn('active_sessions', 'logged_in_at')) {
            Schema::table('active_sessions', function (Blueprint $table) {
                $table->timestamp('logged_in_at')->nullable()->after('is_active');
            });
        }

        if (! Schema::hasColumn('active_sessions', 'last_seen_at')) {
            Schema::table('active_sessions', function (Blueprint $table) {
                $table->timestamp('last_seen_at')->nullable()->after('logged_in_at');
            });
        }

        if (! Schema::hasColumn('active_sessions', 'logged_out_at')) {
            Schema::table('active_sessions', function (Blueprint $table) {
                $table->timestamp('logged_out_at')->nullable()->after('last_seen_at');
            });
        }

        if (! Schema::hasColumn('active_sessions', 'active_duration_seconds')) {
            Schema::table('active_sessions', function (Blueprint $table) {
                $table->unsignedBigInteger('active_duration_seconds')->default(0)->after('logged_out_at');
            });
        }

        DB::table('active_sessions')->orderBy('id')->get()->each(function (object $session): void {
            $fingerprint = hash('sha256', implode('|', [
                $session->user_agent ?? 'unknown',
                $session->ip_address ?? 'unknown',
            ]));

            DB::table('active_sessions')
                ->where('id', $session->id)
                ->update([
                    'browser_name' => 'Unknown',
                    'browser_version' => null,
                    'operating_system' => 'Unknown',
                    'device_type' => 'desktop',
                    'device_family' => 'Unknown device',
                    'device_fingerprint' => $fingerprint,
                    'is_active' => true,
                    'logged_in_at' => $session->created_at,
                    'last_seen_at' => $session->updated_at,
                    'logged_out_at' => null,
                    'active_duration_seconds' => 0,
                ]);
        });

        Schema::table('active_sessions', function (Blueprint $table) {
            $table->index(['user_id', 'is_active']);
            $table->index(['user_id', 'device_fingerprint']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('active_sessions', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'is_active']);
            $table->dropIndex(['user_id', 'device_fingerprint']);
            $table->dropColumn([
                'browser_name',
                'browser_version',
                'operating_system',
                'device_type',
                'device_family',
                'device_fingerprint',
                'is_active',
                'logged_in_at',
                'last_seen_at',
                'logged_out_at',
                'active_duration_seconds',
            ]);
        });
    }
};
