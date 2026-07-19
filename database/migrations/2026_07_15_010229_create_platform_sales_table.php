<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->onDelete('cascade'); // Kis tenant ne khareeda
            $table->foreignId('platform_invoice_id')->nullable()->constrained()->onDelete('cascade'); // Konsi invoice se related hai
            $table->decimal('total', 10, 2)->default(0); // Sale ki amount
            $table->string('status')->default('completed'); // completed, pending, refunded
            $table->string('payment_method')->nullable(); // jazzcash, bank, card, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_sales');
    }
};