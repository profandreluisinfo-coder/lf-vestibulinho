<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('call_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('number'); // número da chamada (ex: 1ª, 2ª, etc.)
            $table->date('date');              // data da chamada
            $table->time('time');              // hora da chamada
            $table->enum('status', ['pending', 'completed'])->default('pending'); // status da chamada
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('call_lists');
    }
};
