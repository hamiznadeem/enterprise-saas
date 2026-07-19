<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('owner_email');
            $table->string('city')->nullable()->after('phone');
            $table->string('location')->nullable()->after('city');
            $table->string('web_access_url')->nullable()->after('location');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['phone', 'city', 'location', 'web_access_url']);
        });
    }
};