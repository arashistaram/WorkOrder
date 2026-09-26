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
        Schema::create('work_order_checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->boolean('is_done')->default(false);

            $table->foreignId('done_by')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->timestamp('done_at')->nullable();

            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['work_order_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_order_checklist_items');
    }
};
