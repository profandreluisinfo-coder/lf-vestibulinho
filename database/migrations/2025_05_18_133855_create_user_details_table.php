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
        Schema::create('user_details', function (Blueprint $table) {

            $table->unsignedBigInteger('user_id')->primary(); // PK e FK ao mesmo tempo

            $table->string('nationality', 1); // 1 = Brasileira, 2 = Estrangeira
            $table->string('doc_type', 1); // 1 = RG, 2 = RNE
            $table->string('doc_number', 11)->unique(); // rg ou rne unica único na tabela toda

            // Contato
            $table->string('phone', 11);

            // Certidão de Nascimento (modelo novo com 32 dígitos)
            $table->string('new_number', 32)->nullable();

            // Certidão de nascimento antiga
            $table->string('fls', 4)->nullable();
            $table->string('book', 10)->nullable();
            $table->string('old_number', 6)->nullable();
            $table->string('municipality', 45)->nullable();

            // Endereço
            $table->string('zip', 8)->index();
            $table->string('street', 60);
            $table->string('number', 10)->nullable();
            $table->string('complement', 20)->nullable();
            $table->string('burgh', 60);
            $table->string('city', 30);
            $table->string('state', 32);

            // Escola onde completou (completará) o ensino fundamental
            $table->string('school_name', 150);
            $table->string('school_city', 30);
            $table->string('school_state', 32);
            $table->year('school_year');
            $table->string('school_ra', 20)->unique(); // ra único na tabela toda



            // Filiação / Responsável Legal
            $table->string('mother', 60);
            $table->string('mother_phone', 11)->nullable();
            $table->string('father', 60)->nullable();
            $table->string('father_phone', 11)->nullable();
            $table->string('responsible', 60)->nullable();
            $table->string('degree', 1)->nullable(); // grau de parentesco
            $table->string('kinship', 45)->nullable(); // caso 'degree' seja "outro"
            $table->string('responsible_phone', 11)->nullable();
            $table->string('parents_email', 60);

            // Alergia
            $table->string('health', 60)->nullable();
            // Educação Especial
            $table->string('accessibility', 60)->nullable();
            // Programas sociais
            $table->string('nis', 11)->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
