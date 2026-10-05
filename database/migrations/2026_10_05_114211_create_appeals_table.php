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
        Schema::create('appeals', function (Blueprint $table) {
            $table->id();

            // Candidato dono do recurso
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Qual pedido indeferido está sendo contestado:
            // 'pne'  = laudo/relatório PcD (tabela pnes)
            // 'lgbt' = nome social (tabela lgbts)
            $table->enum('type', ['pne', 'lgbt']);

            // Número do protocolo dado pela secretaria (para achar o papel)
            $table->string('protocol', 30)->nullable()->unique();
            $table->text('allegations')->nullable();

            // Decisão da secretaria
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->text('observations')->nullable();

            // Quem decidiu e quando (preenchidos só ao deferir/indeferir)
            $table->foreignId('decided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('decided_at')->nullable();

            $table->timestamps();

            // Garante apenas um recurso por tipo para cada candidato
            $table->unique(['user_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appeals');
    }
};