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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            
            // Automatically locks this sale to the specific clinic (tenant)
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            
            // Optional: If a patient buys from pharmacy (Null for walk-in customers)
            $table->foreignId('patient_id')->nullable()->constrained()->nullOnDelete();
            
            // Who processed this sale? (Cashier / Pharmacist)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Unique Sale Invoice Number (e.g., POS-001, INV-002)
            $table->string('sale_number')->unique();
            
            // Financial Breakdown
            $table->decimal('subtotal', 12, 2)->default(0); // Sum of all items before tax/discount
            $table->decimal('tax_percentage', 5, 2)->default(0); // e.g., 16.00 for 16% GST
            $table->decimal('tax_amount', 12, 2)->default(0); // Calculated tax
            $table->decimal('discount_amount', 12, 2)->default(0); // Flat discount given by cashier
            $table->decimal('total_amount', 12, 2)->default(0); // Final payable amount
            
            // Payment Details
            $table->string('payment_method')->default('cash'); // cash, card, online
            $table->string('status')->default('completed'); // completed, refunded
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
