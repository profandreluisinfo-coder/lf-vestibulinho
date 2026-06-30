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
            $table->string('cpf', 11)->nullable()->unique();
            $table->string('name', 100)->nullable();
            $table->date('birth')->nullable();
            $table->unsignedTinyInteger('gender')->nullable();        
            $table->unsignedTinyInteger('nationality')->nullable();
            $table->string('phone', 11)->nullable();            
            $table->string('email', 150)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('zip', 8)->nullable()->index();
            $table->string('street', 60)->nullable();
            $table->string('home', 10)->nullable();
            $table->string('complement', 45)->nullable();
            $table->string('burgh', 45)->nullable();
            $table->string('city', 45)->nullable();
            $table->string('state', 45)->nullable();
            $table->string('nis', 11)->nullable();
            $table->string('health_issue', 100)->nullable();
            $table->enum('role', ['guest', 'user', 'admin'])->default('guest');
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
        Schema::dropIfExists('sessions');
    }
};