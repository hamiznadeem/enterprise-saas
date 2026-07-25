<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Username login ke liye
            $table->string('username')->nullable()->after('name');
            
            // Last login track karne ke liye
            $table->timestamp('last_login_at')->nullable()->after('password_changed_at');
            
            // 2FA Remember Device token
            $table->string('two_factor_remember_token')->nullable()->after('two_factor_recovery_codes');
        });

        // Username per-tenant unique
        Schema::table('users', function (Blueprint $table) {
            $table->unique(['tenant_id', 'username']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['tenant_id', 'username']);
            $table->dropColumn(['username', 'last_login_at', 'two_factor_remember_token']);
        });
    }
};