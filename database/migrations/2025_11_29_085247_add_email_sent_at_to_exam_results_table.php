<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('exam_results', function (Blueprint $table) {
            $table->timestamp('email_sent_at')->nullable()->after('room_number');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropColumn('email_sent_at');
        });
    }
};