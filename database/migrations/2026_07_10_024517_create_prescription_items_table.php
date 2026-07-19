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
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            
            // Automatically locks this item to the specific clinic (tenant)
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            
            // Which prescription does this medicine belong to?
            $table->foreignId('prescription_id')->constrained()->cascadeOnDelete();
            
            // Which medicine is selected?
            $table->foreignId('medicine_id')->constrained()->cascadeOnDelete();
            
            // Dosage details
            $table->string('dosage')->default('1-1-1'); // e.g., 1-1-1 (Morning-Afternoon-Night) or 0-0-1
            $table->integer('days')->default(3); // How many days to take the medicine
            $table->string('instructions')->nullable(); // e.g., "After meals", "With water"
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};
