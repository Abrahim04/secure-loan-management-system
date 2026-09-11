<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g. penalty_per_day, grace_period_days, max_penalty
            $table->string('value');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Seed sensible defaults matching the loan & penalty policy
        DB::table('system_settings')->insert([
            ['key' => 'penalty_per_day', 'value' => '20', 'description' => 'Penalty amount in PHP per day overdue', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'grace_period_days', 'value' => '0', 'description' => 'Days after due date before penalty applies', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'max_penalty', 'value' => '500', 'description' => 'Maximum penalty cap in PHP per payment schedule', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'otp_expiry_minutes', 'value' => '5', 'description' => 'Minutes before an email OTP expires', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'otp_max_attempts', 'value' => '3', 'description' => 'Max OTP verification attempts before lockout', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'otp_resend_cooldown_seconds', 'value' => '60', 'description' => 'Cooldown before a new OTP can be requested', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
