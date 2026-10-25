<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Adiciona as colunas em locations
        Schema::table('locations', function (Blueprint $table) {
            $table->string('name', 100)->nullable();
            $table->string('address', 200)->nullable();
            $table->unsignedInteger('rooms_available')->default(0);
        });

        // 2) Copia os dados de exam_locations para locations
        DB::table('locations')->insertUsing(
            ['name', 'address', 'rooms_available', 'created_at', 'updated_at'],
            DB::table('exam_locations')->select(
                'name',
                'address',
                'rooms_available',
                'created_at',
                'updated_at'
            )
        );

        // 3) Apaga a tabela antiga
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('exam_locations');
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // 1) Recria exam_locations
        Schema::create('exam_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('address', 200);
            $table->unsignedInteger('rooms_available');
            $table->timestamps();
        });

        // 2) Devolve os dados
        DB::table('exam_locations')->insertUsing(
            ['name', 'address', 'rooms_available', 'created_at', 'updated_at'],
            DB::table('locations')->select(
                'name',
                'address',
                'rooms_available',
                'created_at',
                'updated_at'
            )
        );

        // 3) Remove as colunas de locations
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['name', 'address', 'rooms_available']);
        });
    }
};
