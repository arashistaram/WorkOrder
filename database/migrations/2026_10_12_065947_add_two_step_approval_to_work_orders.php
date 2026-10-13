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
        Schema::table('work_orders', function (Blueprint $table) {
            $table->string('approval_stage', 30)
                ->default('pending_manager')
                ->after('approval_status');

            $table->boolean('needs_admin_approval')
                ->default(false)
                ->after('approval_stage');

            $table->foreignId('manager_approved_by')
                ->nullable()
                ->after('approved_at')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('manager_approved_at')
                ->nullable()
                ->after('manager_approved_by');


            $table->foreignId('admin_approved_by')
                ->nullable()
                ->after('manager_approved_at')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('admin_approved_at')
                ->nullable()
                ->after('admin_approved_by');

            $table->index(['approval_stage', 'department_id'], 'wo_stage_dept_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropForeign(['manager_approved_by']);
            $table->dropForeign(['admin_approved_by']);
            $table->dropColumn([
                'approval_stage',
                'needs_admin_approval',
                'manager_approved_by',
                'manager_approved_at',
                'admin_approved_by',
                'admin_approved_at',
            ]);
        });
    }
};
