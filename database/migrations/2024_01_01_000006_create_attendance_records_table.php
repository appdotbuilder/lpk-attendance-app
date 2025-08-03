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
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('training_class_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->enum('status', ['present', 'absent', 'late', 'excused', 'sick'])->default('absent');
            $table->decimal('latitude', 10, 8)->nullable()->comment('GPS latitude for location validation');
            $table->decimal('longitude', 11, 8)->nullable()->comment('GPS longitude for location validation');
            $table->string('location_address')->nullable()->comment('Human readable address');
            $table->text('notes')->nullable();
            $table->string('photo')->nullable()->comment('Attendance photo');
            $table->boolean('is_valid_location')->default(false)->comment('Whether location is within allowed radius');
            $table->timestamps();
            
            // Ensure unique attendance per user per class per date
            $table->unique(['user_id', 'training_class_id', 'date']);
            
            // Add indexes
            $table->index('user_id');
            $table->index('training_class_id');
            $table->index('date');
            $table->index(['user_id', 'date']);
            $table->index(['training_class_id', 'date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};