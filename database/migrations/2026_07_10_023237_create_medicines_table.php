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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            
            // Automatically locks this medicine to the specific clinic (tenant)
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            
            $table->string('name'); // Brand name (e.g., Panadol, Calpol)
            $table->string('generic_name'); // Formula (e.g., Paracetamol 500mg) - CRITICAL FOR SMART SUGGESTIONS
            $table->string('category')->nullable(); // e.g., Painkiller, Antibiotic, Syrup
            
            $table->integer('stock_quantity')->default(0); // How many items are left
            $table->decimal('sale_price', 8, 2)->default(0); // Selling price to patient
            $table->decimal('purchase_price', 8, 2)->default(0); // Cost price (for profit calculation)
            
            $table->date('expiry_date')->nullable(); // When does it expire?
            $table->string('batch_number')->nullable(); // Tracking batch
            
            $table->boolean('is_active')->default(true); // Hide from prescription if out of stock or discontinued
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
