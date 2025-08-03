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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['info', 'warning', 'success', 'urgent'])->default('info');
            $table->foreignId('sender_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('recipient_type', ['all', 'role', 'class', 'individual'])->default('all');
            $table->string('recipient_role')->nullable()->comment('For role-based notifications');
            $table->foreignId('recipient_class_id')->nullable()->constrained('training_classes')->onDelete('cascade');
            $table->foreignId('recipient_user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->boolean('is_read')->default(false);
            $table->timestamp('scheduled_at')->nullable()->comment('For scheduled notifications');
            $table->timestamp('expires_at')->nullable()->comment('When notification expires');
            $table->timestamps();
            
            // Add indexes
            $table->index('sender_id');
            $table->index('recipient_type');
            $table->index('recipient_role');
            $table->index('recipient_class_id');
            $table->index('recipient_user_id');
            $table->index('is_read');
            $table->index(['recipient_type', 'is_read']);
            $table->index('scheduled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};