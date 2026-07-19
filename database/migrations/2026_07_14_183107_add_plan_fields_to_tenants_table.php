<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            // Foreign key hata kar simple unsignedBigInteger use kiya
            $table->unsignedBigInteger('plan_id')->nullable()->after('id');
            $table->boolean('is_active')->default(1);
            $table->string('status')->default('trial'); // trial, active, suspended, expired
            $table->dateTime('will_expire_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['plan_id', 'is_active', 'status', 'will_expire_at']);
        });
    }
};