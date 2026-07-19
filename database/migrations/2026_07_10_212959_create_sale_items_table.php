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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            
            // Automatically locks this item to the specific clinic (tenant)
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            
            // Which sale/cart does this item belong to?
            $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
            
            // ENTERPRISE SCALABILITY MAGIC: 
            // morphs creates 'itemable_type' (e.g., App\Models\Medicine) and 'itemable_id' (e.g., 2)
            // This allows the same table to hold Medicines, Groceries, or Burgers in the future!
            $table->morphs('itemable');
            
            // SNAPSHOT: Freeze the data at the time of sale (If price changes tomorrow, old invoices stay accurate)
            $table->string('item_name'); // e.g., "Calpol"
            $table->decimal('unit_price', 12, 2); // Price of ONE unit at the time of sale
            $table->string('unit_name')->default('Unit'); // e.g., "Tablet" or "Kg"
            
            // Quantity & Total
            $table->integer('quantity')->default(1); // How many units did the customer buy?
            $table->decimal('total_price', 12, 2); // quantity * unit_price
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
