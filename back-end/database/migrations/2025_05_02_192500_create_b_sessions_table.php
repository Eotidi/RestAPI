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
    Schema::create('b_sessions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('table_id')->constrained('tables')->onDelete('cascade');
        $table->foreignId('promotion_id')->nullable()->constrained('promotions')->nullOnDelete();
        $table->dateTime('start_time');
        $table->dateTime('end_time')->nullable();
        $table->decimal('total_amount', 10, 2)->default(0);
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('b_sessions');
}
};
