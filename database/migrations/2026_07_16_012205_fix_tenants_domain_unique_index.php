<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Purana unique constraint drop karo
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropUnique(['domain']);
        });

        // Naya composite unique constraint banao — sirf non-deleted rows check karega
        Schema::table('tenants', function (Blueprint $table) {
            $table->unique(['domain', 'deleted_at']);
        });
    }

    public function down(): void
    {
        // Rollback: Naya drop, purana wapas lao
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropUnique(['domain', 'deleted_at']);
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->unique(['domain']);
        });
    }
};