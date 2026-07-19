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
        Schema::create('tokens', function (Blueprint $table) {
            $table->id();
            
            // Automatically locks this token to the specific clinic (tenant)
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            
            // Which patient is getting this token?
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            
            // Which doctor will they see?
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            
            // Which service they are availing? (Can be null if just general checkup)
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            
            // The actual token number displayed on screen (e.g., "A-1", "B-2")
            $table->string('token_number');
            
            // Current status of the token in the queue
            $table->enum('status', ['waiting', 'in-progress', 'completed', 'cancelled'])->default('waiting');
            
            // Is this a walk-in patient or did they have an appointment?
            $table->boolean('is_walk_in')->default(true);
            
            // Timestamps for tracking when the doctor called them and when they left
            $table->timestamp('called_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tokens');
    }
};
