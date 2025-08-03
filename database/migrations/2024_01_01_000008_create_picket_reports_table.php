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
        Schema::create('picket_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('training_class_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->text('report')->comment('Daily picket report content');
            $table->json('photos')->nullable()->comment('Array of photo paths');
            $table->text('issues')->nullable()->comment('Any issues or problems reported');
            $table->text('suggestions')->nullable()->comment('Suggestions for improvement');
            $table->enum('status', ['submitted', 'reviewed', 'approved'])->default('submitted');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->timestamps();
            
            // Ensure unique report per user per class per date
            $table->unique(['user_id', 'training_class_id', 'date']);
            
            // Add indexes
            $table->index('user_id');
            $table->index('training_class_id');
            $table->index('date');
            $table->index('status');
            $table->index(['training_class_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('picket_reports');
    }
};