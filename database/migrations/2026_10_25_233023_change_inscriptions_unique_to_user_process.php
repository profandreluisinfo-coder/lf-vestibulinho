<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Uma inscrição por usuário POR PROCESSO SELETIVO, qualquer que seja o curso.
 *
 * Troca o unique (user_id, course_id, process_id) por (user_id, process_id).
 * Use esta migration se o banco já tem dados. Se ainda está em desenvolvimento
 * e pode ser recriado, é mais simples corrigir o unique direto na migration
 * original de create_inscriptions_table.
 */
return new class extends Migration
{
    public function up(): void
    {
        // O novo índice falharia se já houver usuário com duas inscrições no mesmo processo
        $duplicates = DB::table('inscriptions')
            ->select('user_id', 'process_id')
            ->groupBy('user_id', 'process_id')
            ->havingRaw('COUNT(*) > 1')
            ->count();

        if ($duplicates > 0) {
            throw new RuntimeException(
                "Existem {$duplicates} usuário(s) com mais de uma inscrição no mesmo processo. "
                . 'Resolva esses casos antes de rodar esta migration.'
            );
        }

        Schema::table('inscriptions', function (Blueprint $table) {
            // 1º o novo: ele também serve de índice para a chave estrangeira de user_id
            $table->unique(['user_id', 'process_id']);

            // 2º remove o antigo (nome padrão: inscriptions_user_id_course_id_process_id_unique)
            $table->dropUnique(['user_id', 'course_id', 'process_id']);
        });
    }

    public function down(): void
    {
        Schema::table('inscriptions', function (Blueprint $table) {
            $table->unique(['user_id', 'course_id', 'process_id']);
            $table->dropUnique(['user_id', 'process_id']);
        });
    }
};