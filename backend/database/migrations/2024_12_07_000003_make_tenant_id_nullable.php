<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'tenant_id')) {
                $table->unsignedBigInteger('tenant_id')->nullable()->change();
            }
        });
        
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'tenant_id')) {
                $table->unsignedBigInteger('tenant_id')->nullable()->change();
            }
        });
        
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'tenant_id')) {
                $table->unsignedBigInteger('tenant_id')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        // No es necesario revertir
    }
};
