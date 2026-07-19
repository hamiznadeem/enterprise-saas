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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained('platform_admins')->nullOnDelete();
            $table->string('action'); // create, update, delete, login
            $table->string('subject_type')->nullable(); // App\Models\Plan
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('description')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
            
            $table->index(['subject_type', 'subject_id']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
