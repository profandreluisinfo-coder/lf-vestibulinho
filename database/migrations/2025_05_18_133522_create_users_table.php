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
            $table->string('cpf', 11)->unique()->nullable();

            $table->string('name', 100)->nullable();
            $table->string('social_name', 100)->nullable();

            $table->date('birth')->nullable();
            // Gênero
            $table->string('gender', 1)->nullable(); // 1 = Masculino, 2 = Feminino, 3 = Outro, 4 = Prefiro não informar
            // PNE: 0 = Não, 1 = Sim
            $table->boolean('pne')->default(false);

            // Endereço de e-mail
            $table->string('email', 150)->unique();
            $table->timestamp('email_verified_at')->nullable();
            
            // Permissões
            $table->string('role')->default('user'); // valores: user, admin, etc.

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