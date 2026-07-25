<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['patients', 'doctors', 'tokens', 'invoices', 'prescriptions', 'sales', 'medicines'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) use ($table) {
                $blueprint->foreignId('branch_id')->nullable()->after('tenant_id')->constrained()->nullOnDelete();
            });
            
            // Index for branch-based queries
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->index('branch_id');
            });
        }
    }

    public function down(): void
    {
        $tables = ['patients', 'doctors', 'tokens', 'invoices', 'prescriptions', 'sales', 'medicines'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropConstrainedForeignId('branch_id');
                $blueprint->dropIndex(['branch_id']);
            });
        }
    }
};