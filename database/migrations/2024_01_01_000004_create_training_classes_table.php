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
        Schema::create('training_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Class name like "Batch 2024-01"');
            $table->string('code')->unique()->comment('Unique class code');
            $table->text('description')->nullable();
            $table->foreignId('instructor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('max_students')->default(30);
            $table->enum('status', ['planned', 'active', 'completed', 'cancelled'])->default('planned');
            $table->json('schedule')->nullable()->comment('Weekly schedule in JSON format');
            $table->text('location')->nullable();
            $table->timestamps();
            
            // Add indexes
            $table->index('instructor_id');
            $table->index('status');
            $table->index(['status', 'start_date']);
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_classes');
    }
};