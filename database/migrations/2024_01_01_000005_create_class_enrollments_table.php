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
        Schema::create('class_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('training_class_id')->constrained()->onDelete('cascade');
            $table->Date('enrolled_at');
            $table->enum('status', ['active', 'completed', 'dropped', 'transferred'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Ensure unique enrollment per user per class
            $table->unique(['user_id', 'training_class_id']);
            
            // Add indexes
            $table->index('user_id');
            $table->index('training_class_id');
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_enrollments');
    }
};