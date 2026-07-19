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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            
            // Automatically locks this invoice to the specific clinic (tenant)
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            
            // Which patient is being billed?
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            
            // Which visit/token is this bill for?
            $table->foreignId('token_id')->constrained()->cascadeOnDelete();
            
            // Financial details
            $table->decimal('doctor_fee', 8, 2)->default(0); // Doctor's consultation fee
            $table->decimal('service_fee', 8, 2)->default(0); // Extra services (e.g., Blood test)
            $table->decimal('total_amount', 8, 2)->default(0); // Sum of doctor_fee + service_fee
            $table->decimal('discount', 8, 2)->default(0); // If receptionist gives any discount
            
            // Payment status
            $table->enum('status', ['paid', 'unpaid', 'partial'])->default('unpaid');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
