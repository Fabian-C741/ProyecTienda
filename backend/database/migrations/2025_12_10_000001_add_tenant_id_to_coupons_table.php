<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * CRÍTICO: Agregar tenant_id a cupones para aislamiento total
     * Cada vendedor tiene sus propios cupones de descuento
     */
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            // Agregar tenant_id como nullable primero (por si hay datos existentes)
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            
            // Cambiar índice único de code a (tenant_id, code)
            // Permite que diferentes tiendas usen el mismo código
            $table->dropUnique(['code']);
        });
        
        // Crear índice compuesto único por tenant
        Schema::table('coupons', function (Blueprint $table) {
            $table->unique(['tenant_id', 'code']);
            $table->index(['tenant_id', 'is_active']);
        });
    }

    /**
     * Revertir cambios
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropUnique(['tenant_id', 'code']);
            $table->dropIndex(['tenant_id', 'is_active']);
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
            
            // Restaurar índice único global
            $table->unique('code');
        });
    }
};
