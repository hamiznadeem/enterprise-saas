<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_branches', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            
            // Purane columns ko nullable kar do — jab tak data migrate nahi hota
            $table->string('branch_name')->nullable()->change();
            $table->string('branch_code')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('user_branches', function (Blueprint $table) {
            $table->dropConstrainedForeignId('branch_id');
        });
    }
};