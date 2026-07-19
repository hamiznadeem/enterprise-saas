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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            
            // Automatically locks this service to the specific clinic (tenant)
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            
            $table->string('name'); // Name of the service (e.g., General Checkup, CBC Test)
            $table->text('description')->nullable(); // Brief details about the service
            $table->decimal('fee', 8, 2)->default(0); // Cost of the service (e.g., 500.00)
            $table->boolean('is_active')->default(true); // Is this service currently available?
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
