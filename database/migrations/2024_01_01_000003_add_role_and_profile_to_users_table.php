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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'instructor', 'cpmi'])->default('cpmi')->after('email');
            $table->string('phone')->nullable()->after('email');
            $table->string('nik')->nullable()->unique()->after('phone')->comment('NIK (Indonesian ID Number)');
            $table->string('birth_place')->nullable()->after('nik');
            $table->date('birth_date')->nullable()->after('birth_place');
            $table->enum('gender', ['male', 'female'])->nullable()->after('birth_date');
            $table->text('address')->nullable()->after('gender');
            $table->string('emergency_contact_name')->nullable()->after('address');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            $table->string('profile_photo')->nullable()->after('emergency_contact_phone');
            $table->enum('status', ['active', 'inactive', 'graduated', 'dropped'])->default('active')->after('profile_photo');
            $table->timestamp('last_seen')->nullable()->after('status');
            
            // Add indexes for better performance
            $table->index('role');
            $table->index('status');
            $table->index(['role', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['status']);
            $table->dropIndex(['role', 'status']);
            
            $table->dropColumn([
                'role', 'phone', 'nik', 'birth_place', 'birth_date', 
                'gender', 'address', 'emergency_contact_name', 
                'emergency_contact_phone', 'profile_photo', 'status', 'last_seen'
            ]);
        });
    }
};