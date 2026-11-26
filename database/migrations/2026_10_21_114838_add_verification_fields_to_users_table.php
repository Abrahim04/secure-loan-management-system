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
            // Core Personal Details (Excluding phone and address which exist in prior migration)[cite: 13]
            $table->date('dob')->nullable();
            $table->string('gender', 50)->nullable();
            $table->string('nationality', 100)->nullable();

            // Valid ID Information
            $table->string('id_type', 100)->nullable();
            $table->string('id_number', 100)->nullable();
            $table->string('id_image_path')->nullable();

            // Employment & Income Details
            $table->string('employment_status', 50)->nullable();
            $table->string('occupation', 255)->nullable();
            $table->string('job_position', 255)->nullable();
            $table->string('monthly_income', 100)->nullable();
            $table->string('income_source', 255)->nullable();

            // Verification Status (unverified, pending, verified)
            $table->string('verification_status', 50)->default('unverified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'dob',
                'gender',
                'nationality',
                'id_type',
                'id_number',
                'id_image_path',
                'employment_status',
                'occupation',
                'job_position',
                'monthly_income',
                'income_source',
                'verification_status',
            ]);
        });
    }
};