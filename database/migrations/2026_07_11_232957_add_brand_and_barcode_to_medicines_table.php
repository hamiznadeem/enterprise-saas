<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            // Brand name name ke baad add hoga
            $table->string('brand_name')->nullable()->after('name');
            
            // Barcode batch_number ke baad add hoga. Index isliye lagaya kyunki POS mein barcode search fast honi chahiye
            $table->string('barcode')->nullable()->index()->after('batch_number');
        });
    }

    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropColumn(['brand_name', 'barcode']);
        });
    }
};
