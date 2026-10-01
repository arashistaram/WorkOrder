<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('output_messenger_accounts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('output_user_id', 100)->nullable()->index();
            $table->string('output_username', 150)->nullable();
            $table->string('output_email', 190)->nullable();
            $table->string('output_mobile', 30)->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('notify_on_assign')->default(true);
            $table->boolean('notify_on_status_change')->default(true);
            $table->timestamp('last_notified_at')->nullable();

            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'notify_on_assign']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('output_messenger_accounts');
    }
};
