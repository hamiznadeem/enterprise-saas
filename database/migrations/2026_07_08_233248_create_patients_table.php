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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            
            // Yeh column automatically add hoga (Hamara Security Guard)
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            
            // Patient Ki Basic Info
            $table->string('name');
            $table->string('phone')->unique(); // Ek clinic mein ek number sirf ek patient ka ho sakta hai
            $table->string('cnic')->nullable(); // Optional (null ho sakta hai agar nahi pata)
            $table->string('age');
            $table->enum('gender', ['male', 'female', 'other']);
            
            // Patient Ki Medical Info
            $table->text('address')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('blood_group')->nullable();
            $table->text('allergies')->nullable(); // Kisi cheez se allergy ho toh likhenge
            $table->longText('medical_history')->nullable(); // Pehle ki bimariyan etc.
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
