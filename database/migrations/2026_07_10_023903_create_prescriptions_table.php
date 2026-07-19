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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            
            // Automatically locks this prescription to the specific clinic (tenant)
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            
            // Which patient and doctor is involved?
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            
            // Which visit/token is this prescription for?
            $table->foreignId('token_id')->constrained()->cascadeOnDelete();
            
            // Medical details
            $table->text('diagnosis')->nullable(); // What is the disease? (e.g., Viral Fever)
            $table->text('notes')->nullable(); // Extra instructions (e.g., Drink lots of water, avoid cold)
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
