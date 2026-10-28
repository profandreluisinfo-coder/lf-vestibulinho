<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Controle de envio do e-mail com o local de prova (antes ficava em exam_results).
 * Só é necessária se a coluna ainda não foi criada em exams.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('exams', 'email_sent_at')) {
            return;
        }

        Schema::table('exams', function (Blueprint $table) {
            $table->timestamp('email_sent_at')->nullable();
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('exams', 'email_sent_at')) {
            Schema::table('exams', function (Blueprint $table) {
                $table->dropColumn('email_sent_at');
            });
        }
    }
};