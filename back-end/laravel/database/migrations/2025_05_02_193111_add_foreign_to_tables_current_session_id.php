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
    Schema::table('tables', function (Blueprint $table) {
        $table->foreign('current_session_id')
              ->references('id')
              ->on('b_sessions')
              ->nullOnDelete();
    });
}

public function down(): void
{
    Schema::table('tables', function (Blueprint $table) {
        $table->dropForeign(['current_session_id']);
    });
}

};
