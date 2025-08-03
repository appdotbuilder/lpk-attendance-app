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
        Schema::create('training_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_class_id')->constrained()->onDelete('cascade');
            $table->string('subject')->comment('Training subject/topic');
            $table->text('description')->nullable();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('room')->nullable();
            $table->text('materials')->nullable()->comment('Required materials or equipment');
            $table->enum('type', ['theory', 'practical', 'exam', 'field_trip', 'orientation'])->default('theory');
            $table->boolean('is_mandatory')->default(true);
            $table->timestamps();
            
            // Add indexes
            $table->index('training_class_id');
            $table->index('date');
            $table->index(['training_class_id', 'date']);
            $table->index(['date', 'start_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_schedules');
    }
};