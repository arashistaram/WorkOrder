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
        Schema::create('work_order_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->cascadeOnDelete();

            $table->foreignId('assigned_to')->constrained('users')->restrictOnDelete();
            $table->foreignId('assigned_by')->constrained('users')->restrictOnDelete();

            $table->foreignId('from_department_id')->nullable()
                ->constrained('departments')->nullOnDelete();
            $table->foreignId('to_department_id')
                ->constrained('departments')->restrictOnDelete();

            $table->text('note')->nullable();
            $table->timestamp('assigned_at');
            $table->timestamp('unassigned_at')->nullable();

            $table->timestamps();

            $table->index(['work_order_id', 'assigned_at']);
            $table->index(['assigned_to', 'unassigned_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_order_assignments');
    }
};
