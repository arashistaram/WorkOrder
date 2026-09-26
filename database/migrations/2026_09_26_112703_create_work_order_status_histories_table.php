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
        Schema::create('work_order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->cascadeOnDelete();

            $table->foreignId('from_status_id')->nullable()
                ->constrained('work_order_statuses')->nullOnDelete();
            $table->foreignId('to_status_id')
                ->constrained('work_order_statuses')->restrictOnDelete();

            $table->foreignId('changed_by')->constrained('users')->restrictOnDelete();
            $table->text('note')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['work_order_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_order_status_histories');
    }
};
