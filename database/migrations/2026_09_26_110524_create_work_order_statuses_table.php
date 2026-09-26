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
        Schema::create('work_order_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('key', 40)->unique();      // draft, pending, assigned, ...
            $table->string('label');                   // "پیش‌نویس", "در انتظار تخصیص", ...
            $table->string('color', 20)->default('gray');

            $table->boolean('is_final')->default(false);   // completed/cancelled
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_order_statuses');
    }
};
