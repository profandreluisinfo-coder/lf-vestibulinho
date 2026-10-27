<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Liberação do local da prova e do resultado passa a ser por processo seletivo.
 *
 * Nulo = não liberado. Preenchido = liberado, e guarda o momento em que o admin liberou.
 * A tabela settings NÃO é removida aqui: remova-a em outra migration, depois de
 * atualizar o código que a utiliza.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('processes', function (Blueprint $table) {
            $table->timestamp('location_released_at')->nullable();
            $table->timestamp('result_released_at')->nullable();
        });

        // Leva o estado atual de settings para o processo vigente
        if (! Schema::hasTable('settings')) {
            return;
        }

        $settings  = DB::table('settings')->first();
        $currentId = DB::table('processes')->orderByDesc('id')->value('id');

        if ($settings && $currentId) {
            DB::table('processes')->where('id', $currentId)->update([
                'location_released_at' => $settings->location ? now() : null,
                'result_released_at'   => $settings->result ? now() : null,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('processes', function (Blueprint $table) {
            $table->dropColumn(['location_released_at', 'result_released_at']);
        });
    }
};