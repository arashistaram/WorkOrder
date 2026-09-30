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
        Schema::create('work_order_priorities', function (Blueprint $table) {
            $table->id();
            $table->string('key', 20)->unique();   // low, medium, high, critical
            $table->string('label');                // "کم", "متوسط", ...
            $table->string('color', 20)->default('gray');
            $table->unsignedTinyInteger('level')->default(0); // 0=کمترین، 4=بیشترین
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_order_priorities');
    }
};
