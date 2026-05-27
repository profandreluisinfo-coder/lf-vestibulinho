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
        Schema::table('user_details', function (Blueprint $table) {
            // adiciona a coluna pne_description do tipo texto, permitindo nulo, após a coluna pne
            $table->text('pne_description')->nullable()->after('pne');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            // remove a coluna pne_description caso a migration seja revertida
            $table->dropColumn('pne_description');
        });
    }
};
