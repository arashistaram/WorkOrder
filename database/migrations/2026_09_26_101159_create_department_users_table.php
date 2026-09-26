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
        Schema::create('department_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('role')->default('member'); // ['supervisor', 'member']

            $table->boolean('is_active')->default(true);
            $table->date('joined_at')->nullable();
            $table->date('left_at')->nullable();

            $table->timestamps();

            $table->unique(['department_id', 'user_id']);

            $table->index(['department_id', 'role', 'is_active']);
            $table->index(['user_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_users');
    }
};
