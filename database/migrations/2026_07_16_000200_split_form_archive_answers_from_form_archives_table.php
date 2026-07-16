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
        if (! Schema::hasColumn('form_archives', 'digital_signature')) {
            Schema::table('form_archives', function (Blueprint $table) {
                $table->text('digital_signature')->nullable()->after('user_id');
            });
        }

        if (Schema::hasColumn('form_archives', 'form_question_id') && Schema::hasColumn('form_archives', 'answer')) {
            DB::table('form_archives')
                ->select(['id', 'form_question_id', 'answer', 'created_at', 'updated_at'])
                ->orderBy('id')
                ->chunkById(100, function ($archives) {
                    $rows = $archives->map(function ($archive) {
                        return [
                            'form_archive_id' => $archive->id,
                            'form_question_id' => $archive->form_question_id,
                            'answer' => $archive->answer,
                            'created_at' => $archive->created_at,
                            'updated_at' => $archive->updated_at,
                        ];
                    })->all();

                    if ($rows !== []) {
                        DB::table('form_archive_answers')->insert($rows);
                    }
                });

            Schema::table('form_archives', function (Blueprint $table) {
                $table->dropForeign(['form_question_id']);
                $table->dropColumn(['form_question_id', 'answer']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
