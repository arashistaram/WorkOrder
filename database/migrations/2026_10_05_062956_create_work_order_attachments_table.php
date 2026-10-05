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
        Schema::create('work_order_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')
                ->constrained('work_orders')
                ->cascadeOnDelete();

            $table->foreignId('status_history_id')
                ->nullable()
                ->constrained('work_order_status_histories')
                ->nullOnDelete();

            $table->foreignId('uploaded_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('original_name');
            $table->string('file_name');
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('size');
            $table->string('disk', 20)->default('local');
            $table->string('path');

            $table->text('note')->nullable();

            $table->timestamps();

            $table->index(['work_order_id', 'created_at']);
            $table->index('uploaded_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_order_attachments');
    }
};
