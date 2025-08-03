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
        Schema::create('training_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('Setting key identifier');
            $table->text('value')->comment('Setting value (JSON for complex data)');
            $table->string('type')->default('string')->comment('Data type: string, integer, boolean, json');
            $table->string('category')->default('general')->comment('Setting category');
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false)->comment('Whether setting is visible to non-admins');
            $table->timestamps();
            
            // Add indexes
            $table->index('key');
            $table->index('category');
            $table->index('is_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_settings');
    }
};