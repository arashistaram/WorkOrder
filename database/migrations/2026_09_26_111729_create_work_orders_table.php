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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('code', 40)->unique();

            $table->string('title');
            $table->text('description')->nullable();

            $table->foreignId('department_id')
                ->constrained('departments')->restrictOnDelete();

            $table->foreignId('assignee_id')->nullable()
                ->constrained('users')->nullOnDelete();

            $table->foreignId('assigned_by')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at')->nullable();

            $table->foreignId('status_id')
                ->constrained('work_order_statuses')->restrictOnDelete();
            $table->foreignId('priority_id')
                ->constrained('work_order_priorities')->restrictOnDelete();

            // approval columns — defined once, in the desired order
            $table->tinyInteger('approval_status')->default(0);
            $table->foreignId('approved_by')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();

            // user who created / transferred the work order from their dept to another
            $table->foreignId('created_by')
                ->constrained('users')->restrictOnDelete();

            $table->date('due_date')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->decimal('estimated_hours', 6, 2)->nullable();
            $table->decimal('actual_hours', 6, 2)->nullable();

            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['department_id', 'status_id'], 'wo_dept_status_idx');
            $table->index(['department_id', 'assignee_id'], 'wo_dept_assignee_idx');
            $table->index(['assignee_id', 'status_id'], 'wo_assignee_status_idx');
            $table->index(['status_id', 'due_date'], 'wo_status_due_idx');
            $table->index(['created_by']);
            $table->index(['priority_id', 'status_id']);
            $table->index(['approval_status', 'created_at'], 'wo_approval_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
