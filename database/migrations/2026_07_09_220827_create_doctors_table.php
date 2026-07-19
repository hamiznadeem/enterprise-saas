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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            
            // SECURITY: Ye line automatically doctor ko uski clinic (tenant) se lock kar degi
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            
            $table->string('name'); // Doctor ka naam (e.g., Dr. Ahmed)
            $table->string('specialization')->nullable(); // Specialist kya hain? (e.g., Skin, Cardiologist)
            $table->decimal('consultation_fee', 8, 2)->default(0); // Fee (e.g., 1500.00)
            $table->string('phone')->nullable(); // Doctor ka contact number
            $table->boolean('is_active')->default(true); // Kya doctor abhi available hai? (True/False)
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
