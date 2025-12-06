<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name'); // mercadopago, stripe, paypal
            $table->string('display_name');
            $table->json('credentials')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_test_mode')->default(true);
            $table->timestamps();
            
            $table->unique(['tenant_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
