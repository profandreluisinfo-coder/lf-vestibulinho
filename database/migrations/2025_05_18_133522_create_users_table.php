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
        Schema::create('users', function (Blueprint $table) {

            $table->id();

            // Dados pessoais
            $table->string('cpf', 11)->nullable()->unique(); // cpf único na tabela toda

            $table->string('name', 100)->nullable();
            $table->string('social_name', 100)->nullable();

            $table->date('birth')->nullable();

            // Gênero
            $table->unsignedTinyInteger('gender')->nullable(); // 1 = Masculino, 2 = Feminino, 3 = Outro, 4 = Prefiro não informar

            $table->unsignedTinyInteger('nationality')->nullable(); // 1 = Brasileira, 2 = Estrangeira
            $table->unsignedTinyInteger('doc_type')->nullable(); // 1 = RG, 2 = RNE
            $table->string('doc_number', 11)->nullable()->unique(); // rg ou rne único na tabela toda

            // Certidão de Nascimento (modelo novo com 32 dígitos)
            $table->string('new_number', 32)->nullable();

            // Certidão de nascimento antiga
            $table->string('fls', 4)->nullable();
            $table->string('book', 10)->nullable();
            $table->string('old_number', 6)->nullable();
            $table->string('municipality', 45)->nullable();

            // Contato
            $table->string('phone', 11)->nullable();

            // Endereço
            $table->string('zip', 8)->nullable();
            $table->string('street', 60)->nullable();
            $table->string('number', 10)->nullable();
            $table->string('complement', 20)->nullable();
            $table->string('burgh', 60)->nullable();
            $table->string('city', 30)->nullable();
            $table->string('state', 32)->nullable();

            // Escola onde completou (completará) o ensino fundamental
            $table->string('school_name', 150)->nullable();
            $table->string('school_city', 30)->nullable();
            $table->string('school_state', 32)->nullable();
            $table->year('school_year')->nullable();
            $table->string('school_ra', 20)->nullable()->unique(); // ra único na tabela toda

            // PNE: 0 = Não, 1 = Sim
            $table->boolean('pne')->default(false);
            $table->string('pne_description')->nullable(); // Educação Especial
            $table->string('pne_report_path')->nullable();

            // Alergia
            $table->string('health', 60)->nullable();
            
            // Programas sociais
            $table->string('nis', 11)->nullable();

            // Filiação
            $table->string('mother_name', 100)->nullable();
            $table->string('mother_phone', 11)->nullable();

            $table->string('father_name', 100)->nullable();
            $table->string('father_phone', 11)->nullable();

            // Responsável legal (caso não seja pai ou mãe)
            $table->string('responsible', 100)->nullable();
            $table->string('degree', 1)->nullable();
            $table->string('kinship', 50)->nullable();
            $table->string('responsible_phone', 11)->nullable();
            $table->string('parents_email', 100)->nullable();

            // Endereço de e-mail
            $table->string('email', 150)->unique();
            $table->timestamp('email_verified_at')->nullable();
            
            // Permissões
            $table->enum('role', ['user', 'admin'])->default('user'); // valores: user, admin, etc.

            // Dados de login
            $table->timestamp('last_login_at')->nullable();

            $table->string('password', 200);
            $table->string('token', 100)->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};