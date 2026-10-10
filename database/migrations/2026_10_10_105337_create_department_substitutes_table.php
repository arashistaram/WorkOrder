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
        Schema::create('department_substitutes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')->cascadeOnDelete();

            $table->foreignId('substitute_for_id')
                ->constrained('users')->cascadeOnDelete();

            $table->foreignId('department_id')
                ->constrained('departments')->cascadeOnDelete();

            $table->string('role', 20)->default('manager');

            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            $table->string('reason')->nullable();

            $table->boolean('is_active')->default(true);

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['user_id', 'is_active'], 'sub_user_active_idx');
            $table->index(['department_id', 'is_active'], 'sub_dept_active_idx');
            $table->index(['substitute_for_id', 'is_active'], 'sub_for_active_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_substitutes');
    }
};
